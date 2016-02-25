<?php
App::import('Core', 'Folder');
class Cake2Shell extends Shell {

    function main() {
        $fh = new Folder(APP . 'View' . DS);
        $files = $fh->findRecursive();
        foreach($files AS $file) {
            $content = file_get_contents($file);
            $offset = 0;
            $newContent = '';
            while(FALSE !== $pos = strpos($content, '$this->Paginator->sort(', $offset)) {
                $newContent .= substr($content, $offset, $pos - $offset);
                $posEnd = strpos($content, ')', $pos) + 1;
                $target = substr($content, $pos, $posEnd - $pos);
                $part1Pos = strpos($target, '\'');
                $part1End = strpos($target, '\'', $part1Pos + 1) + 1;
                $part1Length = $part1End - $part1Pos;
                $part2Pos = strpos($target, '\'', $part1Pos + $part1Length);
                $part2End = strpos($target, '\'', $part2Pos + 1) + 1;
                $part2Length = $part2End - $part2Pos;
                $target = implode('', array(
                    substr($target, 0, $part1Pos),
                    substr($target, $part2Pos, $part2Length),
                    substr($target, $part1End, ($part2Pos - $part1End)),
                    substr($target, $part1Pos, $part1Length),
                    substr($target, $part2End),
                ));
                $newContent .= $target;
                $offset = $posEnd;
            }
            $newContent .= substr($content, $offset);
            file_put_contents($file, $newContent);
        }
    }

}