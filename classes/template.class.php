<?php
/**
 * Template Class
 */
class template {
    /**
     * Template file
     * @var string
     */
    private $file;
    /**
     * Content file
     * @var string
     */
    private $content;
    /**
     * Array of vars and markers
     * @var array
     */
    private $vars;
    /**
     * Array of conditionals and values
     * @var array
     */
    private $conditionals;
    /**
     * Construct
     */
    public function __construct() {
        $this->file = '';
        $this->vars = array();
    }
    /**
     * Set template File
     * @param string $file
     */
    public function setTemplate($file) {
        $this->file = $file;
    }
    /**
     * Makes assoc array of markers and vars
     * @param string $vars
     */
    public function assignVar($vars) {
        foreach ($vars as $varName => $varValue) {
              $this->vars[$varName] = $varValue;
        }
    }
    public function assignConditional($conds) {
        foreach ($conds as $condsName => $condsValue) {
              $this->conditionals[$condsName] = $condsValue;
        }
    }
    /**
     * Loads template
     * @return string
     */
    private function loadContent() {
        if(isset($this->content)) {
            return $content = $this->content;
        } elseif (file_exists($this->file)) {
            return $content = file_get_contents($this->file);
        } else {
            die("No template!");
        }
    }
    /**
     * Replaces markers
     */
    private function varsReplace() {
        $content = $this->loadContent();
        foreach ($this->vars as $varName=>$varValue) {
            $content = str_replace('{' . $varName . '}', $varValue, $content);
        }
        $this->content = $content;
    }
    /**
     * If-else logic
     */
    private function conditionals() {
        $content = $this->loadContent();
        $pattern = "if\(*=*\){*}else{*}";
    }
    /**
     * Displays template
     */
    public function display() {
        $this->varsReplace();
        echo $this->content;
    }
}