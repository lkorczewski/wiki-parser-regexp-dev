wiki-parser-regexp-dev
======================

This project is a small wiki markup parser written in PHP and using regular expressions for parsing. Since purely regexp parsing is quite a challenge, the chief goal was to correctly render a well-formatted markup. 

## Class synopsis
    Wiki_Parser::__construct();
    Wiki_Parser::set_link($prefix, $suffix);
    Wiki_Parser::parse($in);
    Wiki_Parser::clean($in);

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

Headers are restricted to single lines. In case of paragrphs, single line break doesn't influence the rendering.

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

    ...

## Inspiration and references

Writing this version of wiki parser I consulted various sources, most notably:
 * my old attempts at regexp-based wiki parser
 * http://www.wikimatrix.org -- quite a good source 
 * Wiki Creole -- an attempt at standarizing wiki markup
 * Toni's wiky parser -- a minimalistic regexp-only attempt at parsing wiki markup



