# Filelist 0.8.17

Show a list of files for download.

![Screenshot](filelist-screenshot.png?raw=true)

## How to install an extension

[Download ZIP file](https://github.com/GiovanniSalmeri/yellow-filelist/archive/main.zip) and copy it into your `system/extensions` folder. [Learn more about extensions](https://github.com/annaesvensson/yellow-update).

## How to show a list of files

Create a [filelist] shortcut.

The following arguments are available, all but the first argument are optional:

`Folder` = folder under `media/filelist` of files and subfolders to show  
`Extensions` = extensions of files to list, \* for all extensions; wrap multiple extensions into quotes   
`Collapse` = collapse the lists of subfolders  

## How names of files and folders are shown

Trailing digits and hyphens in the names of files and folders are used for sorting, but not shown in the link text.

To display characters forbidden in filenames, use the following mnemonics prefixed with `=`:

| Character | Name | Mnemonic |
|---|---|---|
| : | **c**olon | =c |
| ? | **q**uestion mark | =q |
| / | **s**lash | =s |
| * | **a**sterisk | =a |
| " | **d**ouble quotes | =d |
| < | **l**ess than | =l |
| > | **g**reater than | =g |
| \\ | **b**ackslash | =b |
| \| | **p**ipe | =p |
| = | equal | == |

E.g. the filename `5-Quo vadis=q.pdf` will be displayed as **Quo vadis?**.

But if a text file is found in the same folder, with the same name of the file to be listed and the extension `.text`, its content (just one line) will be displayed as the link text. In this way forbidden characters can be used without resorting to the above encoding.

## Examples

Showing a list of all files in the base folder:

`[filelist]`

Showing a list of files in the subfolder `docs` with various options:

`[filelist docs]`   
`[filelist docs pdf]`   
`[filelist docs "pdf odt"]`   
`[filelist docs * 0]`   

## Settings

The following settings can be configured in file `system/extensions/yellow-system.ini`:

`FilelistLocation` = base location for files   
`FilelistEncode` = use for forbidden characters `mnemo`nics, or [`percent` encoding](https://en.wikipedia.org/wiki/Percent-encoding), or `none`   
`FilelistCollapse` = collapse the view of subfolders   
`FilelistShowType` = show the type of each file   
`FilelistKeepNumbers` = keep leading numbers in descriptions   

## Acknowledgements

This extension includes [Collapsible Lists](https://web.archive.org/web/20210302222959/http://code.iamkate.com/javascript/collapsible-lists/) by Kate Morley. Thank you for the good work.

## Developer

Giovanni Salmeri. [Get help](https://datenstrom.se/yellow/help/).
