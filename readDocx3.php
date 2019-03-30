<?php
include 'parser.php';
function read_docx($filename){
 
 $striped_content = '';
 $content = '';
 $text = '';
 $zip = zip_open($filename);

 if (!$zip || is_numeric($zip)) return false;

 while ($zip_entry = zip_read($zip)) {

     if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
     if (zip_entry_name($zip_entry) == "word/document.xml"){
        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));	
     }else{
        continue;
     }
     zip_entry_close($zip_entry);
 }

 zip_close($zip);

//  $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
//  $content = str_replace('</w:r></w:p>', " ", $content);
  
  // $content = str_replace('<', "&lt", $content);
  // $content = str_replace('>', "&rt", $content);
  
 //$text = extract_text($content);
 $parser = new Parser;
 $parser->extract_meaning($content);
 $content;
}
?>