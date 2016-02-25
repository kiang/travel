<?php
class HotelShell extends Shell {
	var $tasks = array('Thotel', 'Hscc', 'HotelArea');
	var $tmpPath;
	var $types = array(
	    /*
	     * 一般觀光旅館
	     * http://admin.taiwan.net.tw/public/public.asp?selno=134&relno=134
	     */
	    'general_hotels' => 'http://t-hotel.tbroc.gov.tw/politics/generally.asp?page=1&page_size=31',
	    /*
	     * 國際觀光旅館
	     * http://admin.taiwan.net.tw/public/public.asp?selno=133&relno=133
	     */
	    'international' => 'http://t-hotel.tbroc.gov.tw/politics/international.asp?page=1&page_size=63',
	    /*
	     * 一般旅館
	     * http://admin.taiwan.net.tw/public/public.asp?selno=135&relno=135
	     */
	    'hotels' => 'http://hscc.tbroc.gov.tw/list_hp.asp?selType=H&lcount=3000',
	    /*
	     * 民宿
	     * http://admin.taiwan.net.tw/public/public.asp?selno=136&relno=136
	     */
	    'bb' => 'http://hscc.tbroc.gov.tw/list_hp.asp?selType=P&lcount=3000',
	);

    function initialize() {
        $this->uses = array('Hotel', 'Area');
        $this->_loadModels();
        /*
         * 確認目錄與暫存檔案是否存在
         */
        $this->tmpPath = TMP . 'hotels';
        if(!file_exists($this->tmpPath)) {
            mkdir($this->tmpPath);
        }
        /*
        foreach($this->types AS $key => $val) {
            if(!file_exists($this->tmpPath . DS . $key)) {
                file_put_contents($this->tmpPath . DS . $key,
                mb_convert_encoding(file_get_contents($val), 'utf8', 'big5'));
            }
        }
        */
    }

	function main() {
	    $this->out('Interactive Hotel Shell');
	    $this->hr();
	    $this->out('[T]hotel');
	    $this->out('[H]scc');
	    $this->out('[Q]uit');
	    $classToDo = strtoupper($this->in('What would you like to do?',
	    array('H', 'T', 'Q')));
	    switch($classToDo) {
	        case 'T':
	            $this->Thotel->execute();
	            break;
	        case 'H':
	            $this->Hscc->execute();
	            break;
	        case 'Q':
	            exit(0);
	            break;
	        default:
	            $this->out('You have made an invalid selection.');
	    }
		$this->hr();
		$this->main();
	}
}