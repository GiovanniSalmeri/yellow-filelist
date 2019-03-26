# Filelist 0.8.3

Show a list of files for download.

![Screenshot](filelist-screenshot.png?raw=true)

## How to install extension

1. [Download and install Datenstrom Yellow](https://github.com/datenstrom/yellow/).
2. [Download extension](../../archive/master.zip). If you are using Safari, right click and select 'Download file as'.
3. Copy `filelist.zip` into your `system/extensions` folder.

To uninstall delete the [extension files](extension.ini).

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

## Settings

The following settings can be configured in file `system/settings/system.ini`:

`filelistDir` (default = `media/filelist/`) = base directory for files   
`filelistLocation` (default = `/media/filelist/`) = base location for files   
`filelistEncode` (default = `mnemo`) = use `mnemo`nics or [`percent` encoding](https://en.wikipedia.org/wiki/Percent-encoding) for forbidden characters   
`filelistCollapse` (default = `1`) = collapse the view of subfolders   
`filelistShowType` (default = `0`) = show the type of each file   

## Example

Embedding a list of all files in the base folder:

`[filelist]`

Embedding a list of files in the subfolder `docs` with various options:

`[filelist docs]`   
`[filelist docs pdf]`   
`[filelist docs "pdf odt"]`   
`[filelist docs * 0]`   

## Developer

Giovanni Salmeri.
