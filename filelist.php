<?php
// Filelist extension, https://github.com/GiovanniSalmeri/yellow-filelist

class YellowFilelist {
    const VERSION = "0.9.1";
    const META = "text";
    public $yellow;         //access to API

    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->system->setDefault("filelistLocation", "/media/filelist/");
        $this->yellow->system->setDefault("filelistEncode", "mnemo");
        $this->yellow->system->setDefault("filelistCollapse", "1");
        $this->yellow->system->setDefault("filelistShowType", "0");
        $this->yellow->system->setDefault("filelistKeepNumbers", "0");
    }

    // Handle page content element
    public function onParseContentElement($page, $name, $text, $attributes, $type) {
        $output = null;
        if ($name=="filelist" && ($type=="block" || $type=="inline")) {
            list($filePath, $fileExtensions, $collapse) = $this->yellow->toolbox->getTextArguments($text);
            if (substr($filePath, -1)!=="/") $filePath .= "/";
            if (substr($filePath, 0)=="/") $filePath = substr($filePath, 1);
            $filePath = $this->yellow->lookup->normalisePath($filePath);
            $extensions = preg_split("/\s*,\s*/", $fileExtensions, 0, PREG_SPLIT_NO_EMPTY);
            if ($collapse=="") $collapse = $this->yellow->system->get("filelistCollapse");
            $fileLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("filelistLocation").$filePath;
            $filePath = $this->yellow->lookup->findMediaDirectory("filelistLocation").$filePath;
            if ($this->yellow->lookup->isValidFile($filePath) && is_dir($filePath)) {
                $output .= $this->fileDirectory($filePath, $fileLocation, $extensions, $collapse);
            }
        }
        return $output;
    }

    // Return decoded file name
    private function decodeFilename($name) {
        $filelistEncode = $this->yellow->system->get("filelistEncode");
        if ($filelistEncode=="percent") {
            return rawurldecode($name);
        } elseif ($filelistEncode=="mnemo") {
            $replacePairs = ["=l"=>"<", "=g"=>">", "=c"=>":", "=d"=>"\"", "=s"=>"/", "=b"=>"\\", "=p"=>"|", "=q"=>"?", "=a"=>"*", "=="=>"="];
            return strtr($name, $replacePairs);
        } else {
            return $name;
        }
    } 

    // Return description from meta file or file name
    private function getDescription($startDirectory, $name, $isDirectory) {
        $fileName = $isDirectory ? pathinfo($name)["basename"] : pathinfo($name)["filename"];
        $metaHandle = @fopen($startDirectory."/".$fileName.".".$this::META, "r");
        if ($metaHandle) {
            $description = trim(fgets($metaHandle));
            fclose($metaHandle);
        } else {
            $description = $this->decodeFilename($this->yellow->system->get("filelistKeepNumbers") ? $fileName : preg_replace("/^[\d\-]+/", "", $fileName));
        }
        return $description;
    }

    // Recursively return directory list
    private function fileDirectory($startDirectory, $startLocation, $extensions, $collapse) {
        $output = null;
        $directoryHandle = opendir($startDirectory);
        $files = $directories = [];
        while (($entry = readdir($directoryHandle))!==false) {
            if ($entry[0]==".") continue;
            $entryType = $this->yellow->toolbox->getFileType($entry);
            if (is_file($startDirectory.$entry) && $entryType!=$this::META && (!$extensions || in_array($entryType, $extensions))) {
                $files[] = $entry;
            } elseif (is_dir($startDirectory.$entry) && ($entry[0]!=".")) {
                $directories[] = $entry;
            }
        }
        closedir($directoryHandle);
        $output .= "<ul class=\"filelist".($collapse ? " filelist-collapsible" : "")."\">\n";
        natcasesort($directories);
        foreach ($directories as $directory) {
            $description = $this->getDescription($startDirectory, $directory, true);
            $output .= "<li class=\"filelist-directory\"><span>".htmlspecialchars($description)."</span>\n";
            $output .= $this->fileDirectory($startDirectory.$directory."/", $startLocation.$directory."/", $extensions, null);
            $output .= "</li>\n";
        }
        natcasesort($files);
        foreach ($files as $file) {
            $description = $this->getDescription($startDirectory, $file, false);
            $link = implode('/', array_map('rawurlencode', explode('/', $startLocation.$file)));
            $output .= "<li class=\"filelist-file\"><span class=\"filelist-basename\"><a href=\"".htmlspecialchars($link)."\">".htmlspecialchars($description)."</a></span>";
            if ($this->yellow->system->get("filelistShowType")) $output .= " <span class=\"filelist-extension\">".$this->yellow->toolbox->getFileType($file)."</span>";
            $output .= "</li>\n";
        }
        $output .= "</ul>\n";
        return $output;
   }

    // Handle page extra data
    public function onParsePageExtra($page, $name) {
        $output = null;
        if ($name=="header") {
            $assetLocation = $this->yellow->system->get("coreServerBase").$this->yellow->system->get("coreAssetLocation");
            $output .= "<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"{$assetLocation}filelist.css\" />\n";
            $output .= "<script type=\"text/javascript\" defer=\"defer\" src=\"{$assetLocation}filelist.js\"></script>\n";
        }
        return $output;
    }
}
