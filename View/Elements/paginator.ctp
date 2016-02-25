<?php

if (!isset($url)) {
    $url = array();
}
$this->Paginator->options(array('url' => false));
echo $this->Paginator->first('<<', array('url' => $url));
echo ' &nbsp; ' . $this->Paginator->prev('<', array('url' => $url));
echo ' &nbsp; ' . $this->Paginator->numbers(array('url' => $url));
echo ' &nbsp; ' . $this->Paginator->next('>', array('url' => $url));
echo ' &nbsp; ' . $this->Paginator->last('>>', array('url' => $url));