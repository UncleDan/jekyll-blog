<?

// User preferences - BEGIN

$title = "MP3 Tools Downloads";

$scriptname = "index.php"; //the name of this file, originally *dirlist.php*
$hidescript = true;        //if set to *true* the script itself won't be shown in the file list

// User preferences - END


// Credits - BEGIN

/* MatDirLister 0.2 Script
   Created by Mattia Campolese; 23/05/2004 - 08/07/2005
   Email: webmaster@matsoftware.it
   URL: www.matsoftware.it
   -----------------------
   Please read the "Readme.txt" file
   before using this script
*/

// Credits - END


// Functions - BEGIN

function WriteTop() {
	global $title,$scriptname;
	echo("<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\"http://www.w3.org/TR/html4/loose.dtd\">\n");
	echo("<html>\n");
	echo("<head>\n");
	echo("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n");
	echo("<title>".$title."</title>\n");
	echo("<style type=\"text/css\">\n");
	echo(".navigate\n");
	echo("{\n");
	echo("	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;\n");
	echo("	font-weight : normal;\n");
	echo("	font-size : 10px;\n");
	echo("	color: blue;	\n");
	echo("}\n");
	echo(".title\n");
	echo("{\n");
	echo("	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;\n");
	echo("	font-weight : bold;\n");
	echo("	font-size : 14px;\n");
	echo("	color: black;\n");
	echo("}\n");
	echo(".dir\n");
	echo("{\n");
	echo("  	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;\n");
	echo("	font-weight : bold;\n");
	echo("	font-size : 12px;\n");
	echo("	color: navy;\n");
	echo("\n");
	echo("}\n");
	echo(".file\n");
	echo("{\n");
	echo("  	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;\n");
	echo("	font-weight : normal;\n");
	echo("	font-size : 12px;\n");
	echo("	color: blue;\n");
	echo("}\n");
	echo(".copy\n");
	echo("{\n");
	echo("	font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;\n");
	echo("	font-weight : normal;\n");
	echo("	font-size : 10px;\n");
	echo("	color: black;\n");
	echo("}\n");
	echo("\n");
	echo("</style>\n");
	echo("<body>\n");
	echo("<!-- Created with MatDirLister 0.2 - www.matsoftware.it -->\n");
	echo("<div class=\"title\">Directory:</div>\n");
	echo("<a class=\"navigate\" href=\"".$scriptname."\">Home</a>\n");
}

function WriteBottom() {
	echo("<hr>\n");
	echo("<div class=\"copy\" align=\"center\">\n");
	echo("Page created with <b>MatDirLister 0.2</b> by Mattia Campolese<br>\n");
	echo("<a href=\"http://www.matsoftware.it\" title=\"Matsoftware.it web site\">www.matsoftware.it</a> - webmaster @ matsoftware.it\n");
	echo("</div>\n");
	echo("</body>\n");
	echo("</html>\n");
}

// Functions - END


// Main - BEGIN

// Processing variables by "GET" input

$inpath = $_GET['dir'];
$sub = $_GET['sub'];

if (!IsSet($inpath) || ($inpath == "")) 
 {
  $inpath = "";
  $directory = ".";
 } 
 else $directory = explode("|",$inpath); 

if (!IsSet($sub) || ($sub < 0)) 
 {
  $sub = 0;
  $directory = ".";
 }

// Writing the top of HTML page
WriteTop();

// Processing directories

$path = "";
for ($i=0; $i<=$sub; $i++)
 { 
 $folder = chdir("./".$directory[$i]); // quick fix to error "Warning: chdir() [function.chdir]: No such file or directory (errno 2) in [...]/index.php on line 121"
 $path = $path . $directory[$i] . "|";
  if (($directory[$i] != "") and ($directory[$i] != "."))
   echo " - <a class=\"navigate\" href=\"".$scriptname."?dir=". $path . "&sub=".($i+1)."\">" . $directory[$i] . "</a>";
 } 

echo "\n<hr>\n";

// Getting directory's info...
  
$folder = opendir(".");

while ($file = ReadDir($folder))
 {
  $file_array[] = $file;
 }

// Sort the array
sort($file_array); 
 
// Processing Files&Directories list element by element

foreach ($file_array as $file) {

if (($file == ".") || ($file == "..") || (($file == $scriptname) && $hidescript)) continue;

// Checking if it is a directory or a file

if (FileType($file) == dir)  
  {
    if ($inpath != "")
     {
      $path = $inpath . $file . "|";
      $subw = $sub + 1;
     } 
      else
     {  
      $path = $file ."|";
      $subw = 1;
     } 
      
   $listpath[] = "<a class=\"dir\" href=\"".$scriptname."?dir=".$path."&sub=".$subw."\">".$file."</a><br>\n";
  }
 else
  {
   if ($inpath != "")
     $path = str_replace("|","/",$inpath) . $file;
   else
     $path = $file;

   $listfiles[] = "<a class=\"file\" href=\"".$path."\" target=\"_new\">".$file."</a><br>\n";
  }

}
// Printing the list

for ($id=0; $id<count($listpath); $id++) echo $listpath[$id];
for ($id=0; $id<count($listfiles); $id++) echo $listfiles[$id];

// Unsetting all variables
unset($file_array);
unset($file);
unset($folder);
unset($directory);
unset($sub);
unset($subw);
unset($path);
unset($id);
unset($listpath);
unset($listfiles);

// Writing the bottom of HTML page
WriteBottom();

// Main - END

?>