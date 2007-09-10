#!/usr/bin/env ruby
#
#  Created by Glenn Rempe on 2007-09-09.
#  Copyright (c) 2007. All rights reserved.

# Quick and dirty template processing script. It takes
# as an argument the name of the first template script
# and then executes it to standard output.

# code grabbed from:  http://snippets.dzone.com/posts/show/1723

# Usage : test by running something from rails root like:
#
# ./run_erb_template.rb app/views/rcss/test.rcss

require "erb"

class QuickTemplate
   attr_reader :args, :text
   
   def initialize(file)
      @text = File.read(file)
      
      # CSS config is stored in config/css.yml
      # Use variables stored here using something like this in your views (including .rcss templates):
      # CssReset.support_email
      css_config = "config/css.yml"
      if File.exist?(css_config)
        require 'ostruct'
        require 'yaml'
        config = OpenStruct.new(YAML.load_file(css_config))
        self::class::const_set('CssReset', OpenStruct.new(config.send("reset")) )
        self::class::const_set('CssGrid', OpenStruct.new(config.send("grid")) )
        self::class::const_set('CssTypography', OpenStruct.new(config.send("typography")) )
      end
      
   end
   
   def exec
     
     # http://www.ruby-doc.org/stdlib/libdoc/erb/rdoc/
     # ERB#new(str, safe_level=nil, trim_mode=nil, eoutvar='_erbout')
     template = ERB.new(@text, 0, "%<>")
     
     # this is just for testing.  Need to find a way to pull these in from config.
     # or override some reasonable defaults
     #@width = "950px"
     
     result = template.result(binding)
   end
   
end

def erb(file)
   QuickTemplate.new(file).exec()
end

puts erb(ARGV[0])
