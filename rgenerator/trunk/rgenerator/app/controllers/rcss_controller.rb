# This code borrowed from : http://www.misuse.org/science/2006/09/26/dynamic-css-in-ruby-on-rails/

class CoreERR_CSSFileNotFound < StandardError
end

class RcssController < ApplicationController
  
  layout nil
  session :off

  # serve dynamic stylesheet with name defined
  # by filename on incoming URL parameter :rcss
  def rcss
    # :rcssfile is defined in routes.rb
    if @stylefile = params[:rcssfile]
      #prep stylefile with relative path and correct extension
      @stylefile.gsub!(/.css$/, '')
      @stylefile = "/rcss/" + @stylefile + ".rcss"

      #check for existence of @stylefile on filesystem - raise system error if not found
      if not(File.exists?("#{RAILS_ROOT}/app/views#{@stylefile}"))
        raise CoreERR_CSSFileNotFound
      end
      
      # set caching because we have a good css file to ship
      #expires_in 4.hours
      
      # for testing set some values here to make available to the view.
      # later we would want to provide defaults for these, and
      # allow the user to override using the generator UI.
      @width = "950px"
      
      render(:file => @stylefile, :use_full_path => true, :content_type => "text/css")
      
    else #no method/action specified
      render(:nothing => true, :status => 404)
    end #if @stylefile..
    
  end #rcss
  
end