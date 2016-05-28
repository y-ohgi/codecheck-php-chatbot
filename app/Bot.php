<?php
namespace Sprint;

class Bot
{
    public $command = "";
    public $data = "";
    public $hash = "";

    public function __construct($data){
        $this->command = $data["command"];
        $this->data = $data["data"];
    }

    public function generateHash()
    {
        $comascii = $this->strToAscii($this->command);
        $datascii = $this->strToAscii($this->data);
        $merged_ascii = 0;

        if(mb_strlen($comascii) >= 22){
            $ca = scientificNotation($comascii);
            preg_match('/1.([0-9]+)e\+([0-9]+)/', $ca, $match);
            $comascii = $match[1] . $match[2];
            
        }
        if(mb_strlen($datascii) >= 22){
            $da = scientificNotation($datascii);
            preg_match('/1.([0-9]+)e\+([0-9]+)/', $da, $match);
            $datascii = $match[1] . $match[2];
        }

        $merged_ascii = intval($comascii) + intval($datascii);

        $this->hash = dechex($merged_ascii);
    }

    public function strToAscii($str){
        $ascii = "";
        $tmpstr = $str;

        for($i=0; $i<mb_strlen($str); $i++){
            $s = mb_substr($tmpstr, 0 , 1);
            $tmpstr = mb_substr($tmpstr, 1, mb_strlen($tmpstr));
            $ascii .= ord($s);
        }
        
        return $ascii;
    }
}


/**
 * Return scientific notation if after 'e+' was more than 20.
 * If it was less equal than 20, will return normal integer string.
 *
 * e.g.
 * 1000000000000000000000 => 1e+21
 * => return 1.0000000000000000e+21
 *
 * 10000000000000000000 => 1e+19
 * => return 10000000000000000000
 *
 * @param $num integer
 *
 * @return string
 * Note:
 * Since PHP use scientific notation from 1e+19,
 * this function return value with string.
 */
function scientificNotation($num)
{
    if (overE20($num)) {
        return sprintf("%.16e", $num);
    }
    return sprintf("%.0f", $num);
}

function overE20($num)
{
    $sn = sprintf("%e", $num);
    $e = explode("e+", $sn)[1];
    return $e > 20;
}
