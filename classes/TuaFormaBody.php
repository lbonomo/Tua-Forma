<?php

namespace classes;


class TuaFormaBody {

    public function __construct() {

    }
  
    public function tua_forma_body($data) {
        $html = "<h3>Nuevos datos</h3></br>";
        $html .= "<table><tbody>";
        // $html .= "<tr><th>campo</th><th>valor</th></tr>";
        foreach ($data as $key => $value ) {
            $html .= "<tr><td>$key</td><td><b>$value</b></td></tr>";
        }
        $html .= "</tbody></table>";
        $html .= "<br>";
        return $html;
    }
}