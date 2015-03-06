<?php
class InnactiveTagSeeder
extends DatabaseSeeder
{
    public function run()
    {
        $tags = ["unknown","in","and","are","for","the","to","its","can","even","you","dont","is","sometimes","than","at","a","with","other","this","million","from","last","of","your","after","knows","wants","daily","new","more","on","has","an","updated","how","could","without","into","that","turn","will","soon","one","just","lot","now","not","yourself","thanks","what","know","about","but","others","look","them","out","''","off","looking","long","only","newest","youre","isnt",""];
        foreach ($tags as $tag)
        {
            $tag = new Tag();
            $tag->tag = $tag;
            $tag->active = false;
            $tag->save();
        }
    }
}