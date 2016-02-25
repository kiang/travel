<?php

class Finding extends AppModel {

    var $name = 'Finding';
    var $actsAs = array(
        'Geocode.Geocodable',
    );

}