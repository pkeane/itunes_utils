<?php
ini_set('memory_limit', '512M');

$filename = '/Users/pkeane/Music/iTunes/iTunes Library.xml';

$tracks = array();
$track = array();
$reader = new XMLReader();
$reader->XML(file_get_contents($filename));
while ($reader->read()) {
    if ($reader->localName == "plist" && $reader->nodeType == XMLReader::ELEMENT) {
        while ($reader->read()) {
            if ($reader->localName == "dict" && $reader->nodeType == XMLReader::ELEMENT) {
                while ($reader->read()) {
                    if ($reader->localName == "dict" && $reader->nodeType == XMLReader::ELEMENT) {
                        while ($reader->read()) {
                            if ($reader->localName == "key" && $reader->nodeType == XMLReader::ELEMENT) {
                                $reader->read();
                                $key = $reader->value;
                            }
                            if ($reader->localName != "key" && $reader->nodeType == XMLReader::ELEMENT) {
                                $reader->read();
                                $value = trim($reader->value);
                                if ($key && $value) {
                                    $track[$key] = $value;
                                }
                            }
                            if ($reader->localName == "dict" && $reader->nodeType == XMLReader::END_ELEMENT) {
                                if (count($track) > 3 && !isset($track['Playlist ID'])) {
                                    $tracks[] = $track;
                                }
                                $track = array();
                            }
                        }
                    }
                }
            }
        }
    }
}
$reader->close();
print json_encode($tracks, JSON_PRETTY_PRINT);

