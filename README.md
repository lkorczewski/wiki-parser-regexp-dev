wiki-parser-regexp-dev
======================

This project is a small wiki markup parser written in PHP and using regular expressions for parsing. Since purely regexp parsing is quite a tricky task, the chief goal was restricted to correct rendering of a well-formatted markup.

WARNING! This is the development version, which means it is unfinished, it contains outright errors and it is generally unsuitable for serious usage.

## Class synopsis

**Wiki_Parser::__construct()**  
constructor

**Wiki_Parser::set_link($prefix, $suffix)**  
defines the internal link by prefix (`$prefix`) and suffix (`$suffix`) of the page identifier

**Wiki_Parser::parse($in)**  
parses markup into HTML; the markup syntax is defined in the following part of this documentation

**Wiki_Parser::clean($in)**  
performs some basic cleaning tasks on a markup text, chiefly removing some leading and trailing whitespaces; intended for use prior to storing the markup text

## Wiki markup
    
The chosen wiki markup is an arbitrary selection based on the knowledge of existing markups.

There are some general principles:
 * Every element of block markup like headers, paragraphs, lists and tables needs to be separated from each other by an empty line.

### Inline markup

    //italics//
    **bold**
    $$monospaced$$

    [[link]]
    [[caption|link]]
    [[caption|http://external.link.net]]

### Headers and paragraphs
    
    ! Title
    
    !! Chapter
    
    !!! Section
    
    !!!! Subsection
    
    Paragraph written in a continuous way.
    
    Still
    one
    paragraph.

Headers are restricted to single lines. In case of paragraphs, single line break doesn't influence the rendering.

### Lists

#### Unordered list

    * element
    * element
    ** element
    ** element
    *** element
    ** element
    * element

#### Ordered list
    
    # element
    # element
    ## element
    ## element
    ### element
    ## element
    # element

#### Mixed list
    
    * element
    * element
    #* element
    #* element
    #*# element
    #* element
    * element

### Tables

    [...]

## Inspiration and references

Writing this version of wiki parser I consulted various sources, most notably:
 * my old unpublished attempts at regexp-based wiki parser
 * [WikiMatrix] (http://www.wikimatrix.org/) -- a good source on various wikis, including markup comparison
 * [Wiki Creole] (http://www.wikicreole.org/) -- an attempt at standarizing wiki markup
 * [Toni Lähdekorpi's Wiky.php] (https://github.com/lahdekorpi/Wiky.php) -- a minimalistic regexp-only attempt at parsing wiki markup in PHP
