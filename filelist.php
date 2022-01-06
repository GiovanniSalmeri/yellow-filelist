<?php
// Filelist extension, https://github.com/GiovanniSalmeri/yellow-filelist

class YellowFilelist {
    const VERSION = "0.8.16";
    public $yellow;         //access to API
    public $output;

    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("filelistDirectory", "media/filelist/");
        $this->yellow->system->setDefault("filelistLocation", "/media/filelist/");
        $this->yellow->system->setDefault("filelistEncode", "mnemo");
        $this->yellow->system->setDefault("filelistCollapse", "1");
        $this->yellow->system->setDefault("filelistShowType", "0");
        $this->yellow->system->setDefault("filelistKeepNumbers", "0");
    }
    
    // Handle page content of shortcut
    public function onParseContentShortcut($page, $name, $text, $type) {
        $this->output = null;
        if ($name=="filelist" && ($type=="block" || $type=="inline")) {
            list($filePath, $fileExts, $collapse) = $this->yellow->toolbox->getTextArguments($text);
            if (substr($filePath, -1) !== "/") $filePath = $filePath."/";
            $exts = preg_split("/[\s,]+/", $fileExts, 0, PREG_SPLIT_NO_EMPTY);
            if ($collapse == "") $collapse = $this->yellow->system->get("filelistCollapse");
            $fileLoc = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("filelistLocation").$filePath;
            $filePath = $this->yellow->system->get("filelistDirectory").$filePath;

            if (is_dir($filePath)) {
                $this->fileDir($filePath, $fileLoc, $exts, $collapse);
            }
        }
        return $this->output;
    }

    function decodeFilename($name) {
        if ($this->yellow->system->get("filelistEncode") == "percent") {
            //%3D%3C%3E%3A%22%5C%2F%7C%3F%2A
            //=<>:"\/|?*
            return rawurldecode($name);
        } elseif ($this->yellow->system->get("filelistEncode") == "mnemo") {
            return preg_replace_callback("/=./", function($m) {
                switch ($m[0]) {
                    case "==": return "="; break;
                    case "=l": return "<"; break;
                    case "=g": return ">"; break;
                    case "=c": return ":"; break;
                    case "=d": return "\""; break;
                    case "=s": return "/"; break;
                    case "=b": return "\\"; break;
                    case "=p": return "|"; break;
                    case "=q": return "?"; break;
                    case "=a": return "*"; break;
                    default: return $m[0][1];
                }
            }, $name);
        } else {
            return $name;
        }
    } 

    function getDesc($startDir, $name, $isDir) {
        $filename = $isDir ? pathinfo($name)["basename"] : pathinfo($name)["filename"];
        $metaHandle = @fopen($startDir."/".$filename.".".META, "r");
        if ($metaHandle) {
            $desc = trim(fgets($metaHandle));
            fclose($metaHandle);
        } else {
            $desc = $this->decodeFilename($this->yellow->system->get("filelistKeepNumbers") ? $filename : preg_replace("/^[\d\-]+/", "", $filename));
        }
        return $desc;
    }

    function fileDir($startDir, $startLoc, $exts, $collapse) {
        define("META", "text");
        $dirHandle = opendir($startDir);
        $files = $dirs = [];
        while (($entry = readdir($dirHandle)) !== FALSE) {
            if ($entry[0] == ".") continue;
            $entryType = $this->yellow->toolbox->getFileType($entry);
            if (is_file($startDir.$entry) && $entryType != META && (!$exts || in_array($entryType, $exts))) {
                $files[] = $entry;
            } elseif (is_dir($startDir.$entry) && ($entry[0] != ".")) {
                $dirs[] = $entry;
            }
        }
        closedir($dirHandle);
        $this->output .= "<ul class=\"filelist".($collapse ? " collapsibleList" : "")."\">\n";
        natcasesort($dirs);
        foreach ($dirs as $dir) {
            $desc = $this->getDesc($startDir, $dir, true);
            $this->output .= "<li class=\"directory\">".htmlspecialchars($desc)."\n";
            $this->fileDir($startDir.$dir."/", $startLoc.$dir."/", $exts, null);
            $this->output .= "</li>\n";
        }
        natcasesort($files);
        foreach ($files as $file) {
            $desc = $this->getDesc($startDir, $file, false);
            $link = implode('/', array_map('rawurlencode', explode('/', $startLoc. $file)));
            $this->output .= "<li class=\"file\"><a href=\"".$link."\">".htmlspecialchars($desc)."</a>";
            if ($this->yellow->system->get("filelistShowType")) $this->output .= " <span class=\"filetype\">".$this->yellow->toolbox->getFileType($entry)."</span>";
            $this->output .= "</li>\n";
        }
        $this->output .= "</ul>\n";
   }
    
    // Handle page extra data
    public function onParsePageExtra($page, $name) {
        $output = null;
        if ($name == "header") {
            $extensionLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreExtensionLocation");
            $output .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$extensionLocation}filelist.css\" />\n";
            $output .= "<script type=\"text/javascript\" defer=\"defer\" src=\"{$extensionLocation}filelist.js\"></script>\n";
        }
        return $output;
    }
}
