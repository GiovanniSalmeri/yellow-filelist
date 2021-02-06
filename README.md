# Filelist 0.8.16

Show a list of files for download.

![Screenshot](filelist-screenshot.png?raw=true)

## How to add a list of files

Create a [filelist] shortcut.

The following arguments are available, all but the first argument are optional:

`Folder` (default: `/`) = folder under `media/filelist` of files and subfolders to show  
`Extensions` (default: `*`) = extensions of files to list, \* for all extensions; wrap multiple extensions into quotes   
`Collapse` (default: `1`) = collapse the lists of subfolders  

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

## Example

Embedding a list of all files in the base folder:

`[filelist]`

Embedding a list of files in the subfolder `docs` with various options:

`[filelist docs]`   
`[filelist docs pdf]`   
`[filelist docs "pdf odt"]`   
`[filelist docs * 0]`   

## Settings

The following settings can be configured in file `system/extensions/yellow-system.ini`:

`filelistDir` (default = `media/filelist/`) = base directory for files   
`filelistLocation` (default = `/media/filelist/`) = base location for files   
`filelistEncode` (default = `mnemo`) = use for forbidden characters `mnemo`nics, or [`percent` encoding](https://en.wikipedia.org/wiki/Percent-encoding), or `none`   
`filelistCollapse` (default = `1`) = collapse the view of subfolders   
`filelistShowType` (default = `0`) = show the type of each file   
`filelistKeepNumbers` (default = `0`) = keep leading numbers in descriptions   

## Installation

[Download extension](https://github.com/GiovanniSalmeri/yellow-filelist/archive/master.zip) and copy zip file into your `system/extensions` folder. Right click if you use Safari.

For collapsing the lists this extension uses [Collapsible Lists](http://code.iamkate.com/javascript/collapsible-lists/) by Kate Morley.

## Developer

Giovanni Salmeri. [Get help](https://github.com/GiovanniSalmeri/yellow-filelist/issues).
