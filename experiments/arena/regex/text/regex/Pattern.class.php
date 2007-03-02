<?php
/* This class is part of the XP framework
 *
 * $Id$ 
 */

  /**
   * Represents a regular expression pattern
   *
   * @see      php://preg
   * @purpose  Regular Expression
   */
  class Pattern extends Object {
    const 
      CASE_INSENSITIVE = 0x0001,
      MULTILINE        = 0x0002,
      DOTALL           = 0x0004,
      EXTENDED         = 0x0008,
      ANCHORED         = 0x0010,
      DOLLAR_ENDONLY   = 0x0020,
      ANALYSIS         = 0x0040,
      UNGREEDY         = 0x0080,
      UTF8             = 0x0100;
      
    protected static $flags= array(
      self::CASE_INSENSITIVE => 'i',
      self::MULTILINE        => 'm',
      self::DOTALL           => 's',
      self::EXTENDED         => 'x',
      self::ANCHORED         => 'A',
      self::DOLLAR_ENDONLY   => 'D',
      self::ANALYSIS         => 'S',
      self::UNGREEDY         => 'U',
      self::UTF8             => 'u',
    );
  
    /**
     * Constructor
     *
     * @param   string regex
     * @param   int flags default 0 bitfield of pattern flags
     * @throws  lang.FormatException
     */
    protected function __construct($regex, $flags= 0) {
      $modifiers= '';
      foreach (self::$flags as $bit => $str) {
        if ($bit == $flags & $bit) $modifiers.= $str;
      }
    
      $this->regex= '�'.str_replace('�', '\�', $regex).'�'.$modifiers;
      $n= preg_match($this->regex, '');
      if (FALSE === $n || PREG_NO_ERROR != preg_last_error()) {
        throw new FormatException('Pattern "'.$regex.'" not well-formed');
      }
    }
    
    /**
     * Creates a string representation of this object
     *
     * @return  string
     */
    public function toString() {
      return $this->getClassName().'<'.$this->regex.'>';
    }

    /**
     * Returns how many times a given input is matched.
     *
     * @param   string input
     * @return  int
     * @throws  lang.FormatException
     */  
    public function matches($input) {
      $n= preg_match_all($this->regex, $input, $m);
      if (FALSE === $n || PREG_NO_ERROR != preg_last_error()) {
        throw new FormatException('Pattern "'.$this->regex.'" not well-formed');
      }
      return $n;
    }
    
    /**
     * Compiles a pattern and returns the object
     *
     * @param   string regex
     * @param   int flags default 0 bitfield of pattern flags
     * @return  text.regex.Pattern
     * @throws  lang.FormatException
     */
    public static function compile($regex, $flags= 0) {
      return new self($regex, $flags);
    }
  }
?>
