<?php
include 'QuesDataModel.php'
class Parser{
    var $pos,$content;
    function extract_text($endtag){
        $text='';
        while($this->pos<strlen($this->content) && !$this->matches($endtag)){
            if($this->matches('<w:t>')){
                $this->go_to_end();
                while($this->pos<strlen($this->content) &&!$this->matches('</w:t>')){
                    $text.=$this->content[$this->pos];
                    $this->pos++;
                }
            }
            $this->pos++;
        }
        return $text;
    }
    function matches($str){
        
        $end=0;
        for($i=0;$i<strlen($str) && $this->pos+$i<strlen($this->content) ;$i++){
            if($str[$i]!=$this->content[$this->pos+$i]){
                return false;
            }
            $end=$this->pos+$i;
        }
        $this->pos=$end;
        return true;
    }
    function go_to_end(){
        for(;$this->pos<strlen($this->content);$this->pos++){
            if($this->content[$this->pos]=='>'){
                $this->pos++;
                return;
            }
        }
    }

    function parse_line($line){
        $pos=0;
        $arr;
        while($pos<strlen($line) && $line[$pos]==" "){
            $pos++;
        }
        if($pos==strlen($line)){
            return array('nouse');
        }
        $i=$pos;
        if($line[$i]<='9' && $line[$i]>='0'){
            while($i<strlen($line) && $line[$i]<='9' && $line[$i]>='0'){
                $i++;
            }
            if($i<strlen($line) && ($line[$i]==')' || $line[$i]=='.')){
                $pos=$i+1;
                $ques_text="";
                while($pos<strlen($line)){
                    $ques_text.=$line[$pos];
                    $pos++;
                }
                return array('ques',$ques_text);
            }else return array('simple',$line);
        }else if(($line[$i]<='z' && $line[$i]>='a') || ($line[$i]<='Z' && $line[$i]>='A')){
            $i++;
            if($i<strlen($line) && $line[$i]==')' || $line[$i]=='.'){
                $pos=$i+1;
                $opt_text="";
                while($pos<strlen($line)){
                    $opt_text.=$line[$pos];
                    $pos++;
                }
                return array('opt',$opt_text);
            }else{
                return array('simple',$line);
            }
        }else return array('simple',$line);
    }

    function extract_meaning($content){
        $this->pos=0;
        $this->content=$content;
        $in_p=0;
        $in_q=0;
        $q_no=1;
        $in_opt=0;
        $is_tc=0;
        $opt_no=1;
        for(;$this->pos<strlen($this->content);$this->pos++){
            if($this->matches("<w:p ")){
                $this->go_to_end();
                $in_p++;
                $line = $this->extract_text('</w:p>');
                
                $arr = $this->parse_line($line);
                
                if($arr[0] == 'ques'){
                    $in_q=1;
                    $opt_no=1;
                    echo "Q".$q_no."\t".$arr[1]."<br/>";
                    $q_no++;
                }else if($in_q==1 && $arr[0]=='opt'){
                    echo "opt".$opt_no."\t".$arr[1]."<br/>";
                    $opt_no++;
                }else{
                    echo $arr[0].$line."<br/>";
                }
            }else if($this->matches("<w:tbl>")){
                $tab='<table border="1">';
                for(;$this->pos<strlen($this->content);$this->pos++){
                    if($this->matches('<w:tr ')){
                        $tab.='<tr>';
                        for(;$this->pos<strlen($this->content);$this->pos++){
                            if($this->matches('<w:tc>')){
                                $tab.='<td>';
                                $tab.=$this->extract_text('</w:tc>');
                                $tab.='</td>';
                            }else if($this->matches('</w:tr>')){
                                break;
                            }
                        }
                        $tab.='</tr>';
                    }
                }
                $tab.='</table>';
                echo $tab;
            }else if($this->matches('<w:img')){

            }
        }
    }

}

?>