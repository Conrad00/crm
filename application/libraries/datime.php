<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
// By h.aliaghaee at Monday ,27 Jul 2011

class datime{
	
	
	function __construct(){
  
     //initialiazes
     
    }
	public function jdate($format,$timestamp=''){
		 // example w j M Y H:i:s a
		 
		 /* format :
		  * Y      : 4 digit of year like 1390
		  * y      : 2 digit of year like 90
		  * m      : Numeric month  01-31
		  * n      : Numeric month without leading zeros like 1-12
		  * d      : Numeric day of month 01-31
		  * j      : Numeric day of month without leadin zeros 1-31
		  * M      : Month Name like اردیبهشت
		  * w or W : Week day Name  like   دوشنبه
		  * h      : 12 hour with leadin zeros 01-12
		  * H      : 24 hour with leadin zeros 01-24
		  * i      : Minute with leading zeros  00-59
		  * s      : Seconds with eading zeros  00-59
		  * a or A : am or pm
		  */ 
		$timestamp=="" && $timestamp=time();
		$ymd=date("Ymd",$timestamp);
		$y=substr($ymd,0,4);
		$m=substr($ymd,4,2);
		$d=substr($ymd,6,2);
		list($jy,$jm,$jd)=$this->gregorian_to_jalali($y,$m,$d);
		
		
		$weekdays=array("شنبه","یکشنبه","دوشنبه","سه شنبه","چهارشنبه","پنجشنبه","جمعه");
		$monthes=array("فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند");
		$ampm=array("am"=>"قبل ظهر","pm"=>"بعد ظهر");
		
	    strpos($format,"H") && $format=str_ireplace("a","",$format);
	
		$format=str_replace("Y",$jy,$format);
		$format=str_replace("y",substr($jy,2,2),$format);
		$format=str_replace("m",$jm,$format);
		$format=str_replace("n",(int)$jm,$format);
		$format=str_replace("d",$jd,$format);
		$format=str_replace("j",(int)$jd,$format);  
		$format=str_ireplace("w",$weekdays[date("w",$timestamp)],$format);   
		$format=str_replace("M",$monthes[(int)$jm],$format);  
		$format=str_replace("h",date("h",$timestamp),$format);
		$format=str_replace("H",date("H",$timestamp),$format);
		$format=str_replace("i",date("i",$timestamp),$format);
		$format=str_replace("s",date("s",$timestamp),$format);
		
		$format=str_ireplace("a",date("a",$timestamp),$format);
		
		$format=str_ireplace("am",$ampm["am"],$format);  
		$format=str_ireplace("pm",$ampm["pm"],$format);  
		
		return $format;
		
	}
////////////////////////////////////////////// 	/* is Ok 2011/10/03 change babak.  */
	
	public function jalali_to_timestamp_full($jalali_date,$h="00",$i="00",$s="00"){
		
		$pos_cut_Ym=strpos($jalali_date,'/',0);
		$pos_cut_md=strpos($jalali_date,'/',($pos_cut_Ym+1));
		$len=strlen($jalali_date);

		  $y=substr($jalali_date,0,$pos_cut_Ym);
		  $m=substr($jalali_date,($pos_cut_Ym+1),(($pos_cut_md+1)-($pos_cut_Ym+1)));
		  $d=substr($jalali_date,($pos_cut_md+1),($len-$pos_cut_md));
 
		  $h=="" && $h=date("H");
		  $i=="" && $i=date("i");
		  $s=="" && $s=date("s");
		  list($gy,$gm,$gd)=$this->jalali_to_gregorian($y,$m,$d);
		  return mktime($h,$i,$s,$gm,$gd,$gy);
		
    }

///////////////////////////////////////////////////	
	
	public function jalali_to_timestamp($y,$m,$d,$h="",$i="",$s=""){
		
		  $h=="" && $h=date("H");
		  $i=="" && $i=date("i");
		  $s=="" && $s=date("s");
		  list($gy,$gm,$gd)=$this->jalali_to_gregorian($y,$m,$d);
		  return mktime($h,$i,$s,$gm,$gd,$gy);
		
    }
    
    
    public function gregorian_to_timestamp($y,$m,$d,$h="",$i="",$s=""){
		
		  $h=="" && $h=date("H");
		  $i=="" && $i=date("i");
		  $s=="" && $s=date("s");
		  return mktime($h,$i,$s,$m,$d,$y);
		
    }
	

	public function gregorian_to_jalali ($g_y, $g_m, $g_d)
	{
		
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

		$gy = $g_y-1600;
		$gm = $g_m-1;
		$gd = $g_d-1;

		$g_day_no = 365*$gy+$this->div($gy+3,4)-$this->div($gy+99,100)+$this->div($gy+399,400);

		for ($i=0; $i < $gm; ++$i)
		$g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
		/* leap and after Feb */
		$g_day_no++;
		$g_day_no += $gd;

		$j_day_no = $g_day_no-79;

		$j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;

		$jy = 979+33*$j_np+4*$this->div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */

		$j_day_no %= 1461;

		if ($j_day_no >= 366) {
			$jy += $this->div($j_day_no-1, 365);
			$j_day_no = ($j_day_no-1)%365;
		}

		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
		$j_day_no -= $j_days_in_month[$i];
		$jm = $i+1;
		$jd = $j_day_no+1;

        $jm<10 && $jm="0$jm";
        $jd<10 && $jd="0$jd";
		return array($jy, $jm, $jd);
		
	}

	function jalali_to_gregorian($j_y, $j_m, $j_d)
	{
		
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



		$jy = $j_y-979;
		$jm = $j_m-1;
		$jd = $j_d-1;

		$j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
		for ($i=0; $i < $jm; ++$i)
		$j_day_no += $j_days_in_month[$i];

		$j_day_no += $jd;

		$g_day_no = $j_day_no+79;

		$gy = 1600 + 400*$this->div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097;

		$leap = true;
		if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
		{
			$g_day_no--;
			$gy += 100*$this->div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524;

			if ($g_day_no >= 365)
			$g_day_no++;
			else
			$leap = false;
		}

		$gy += 4*$this->div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$g_day_no %= 1461;

		if ($g_day_no >= 366) {
			$leap = false;

			$g_day_no--;
			$gy += $this->div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}

		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
		$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i+1;
		$gd = $g_day_no+1;
		
        $gm<10 && $gm="0$gm";
        $gd<10 && $gd="0$gd";
		return array($gy, $gm, $gd);
		
	}	
	
	private function div($a,$b) {
		
		return (int) ($a / $b);
		
	}
	
	
 }