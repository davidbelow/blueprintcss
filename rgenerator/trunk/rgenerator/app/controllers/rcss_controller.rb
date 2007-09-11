# This code borrowed from : http://www.misuse.org/science/2006/09/26/dynamic-css-in-ruby-on-rails/

class RcssError_CSSFileNotFound < StandardError
end

class RcssController < ApplicationController
  
  layout nil
  #session :off
  
  def index
  end
  
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
        raise RcssError_CSSFileNotFound
      end
      
      # set caching because we have a good css file to ship
      #expires_in 4.hours
      
      
      case params[:rcssfile]
        when "test"
          # do nothing
        when "reset"
          # do nothing
        when "grid"
          @page_width = params[:page_width].nil? ? CssGrid.page_width : params[:page_width]
          @column_width = params[:column_width].nil? ? CssGrid.column_width : params[:column_width]
          @margin_width = params[:margin_width].nil? ? CssGrid.margin_width : params[:margin_width]
          @number_of_columns = params[:number_of_columns].nil? ? CssGrid.number_of_columns : params[:number_of_columns]
        when "typography"
          # do nothing
      end
      
      
      render(:file => @stylefile, :use_full_path => true, :content_type => "text/css")
      
    else #no method/action specified
      render(:nothing => true, :status => 404)
    end #if @stylefile..
    
  end #rcss
  
end
