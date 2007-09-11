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


## removed the PHP versions with my working Ruby versions. YES, I'm overly .to_i inside of them, but that was just because I wasn't entirely sure what values I would be getting in. The methods were taken from a CLI script.

# ## METHODS:
# # All methods sanitzes the input values as well, just to be extra safe ;-)
# 
# # calculates the column width based on the given page width, margin and number of columns:
# # rounds the value off to the nearest full integer value i.e: 48.33333  => 48
# def calculate_column_width(total_page_width,margin_width,number_of_columns)
#   (((total_page_width.to_i + margin_width.to_i) / number_of_columns.to_i) - margin_width.to_i).to_i
# end
# # calculates the page width based on the given column width, margin and number of columns:
# def calculate_page_width(column_width,margin_width,number_of_columns)
#   (((column_width.to_i + margin_width.to_i) * number_of_columns.to_i) - margin_width.to_i).to_i
# end
# # generates the .span-N CSS code chunk.
# def spans(number_of_columns,column_width,margin_width)
#   out = "\n"
#   1.upto(number_of_columns.to_i) do |n|
#     pad = pad_output(number_of_columns,n) # always style the output :-)
#     out += ".span-#{n} #{pad}{ width: #{(((column_width.to_i + margin_width.to_i) * n) - margin_width.to_i)}px;}\n"
#   end
#   out[-2,1] = " margin: 0; }\n"
#   out
# end
# # generates the .append-N CSS code chunk.
# def appends(number_of_columns,column_width,margin_width)
#   out = "\n"
#   1.upto(number_of_columns.to_i-1) do |n|
#     pad = pad_output(number_of_columns,n) # always style the output :-)
#     out += ".append-#{n} #{pad}{ padding-right: #{((column_width.to_i + margin_width.to_i) * n)}px;}\n"
#   end
#   out
# end
# # generates the .prepend-N CSS code chunk.
# def prepends(number_of_columns,column_width,margin_width)
#   out = "\n"
#   1.upto(number_of_columns.to_i-1) do |n|
#     pad = pad_output(number_of_columns,n) # always style the output :-)
#     out += ".prepend-#{n} #{pad}{ padding-left: #{((column_width.to_i + margin_width.to_i) * n)}px;}\n"
#   end
#   out
# end
# # pads the output to line up the { } in the code
# def pad_output(number_of_columns,n)
#   case n
#   when 1...10
#     pad = (number_of_columns >=100) ? '  ' : ' '
#   when 10...100
#     pad = (number_of_columns >=100) ? ' ' : ''
#   end
# end
# 
# ## /METHODS:



## TESTBED 

# puts calculate_column_width(950, 10, 24)
# puts calculate_column_width(950.56557, 10.3, 24.2)
# 
# puts calculate_page_width(30,10,24)
# puts calculate_page_width(30.9,10.009999,24.3)
# 
# puts spans(24,30,10)
# puts appends(24,30,10)
# puts prepends(101,30,10)
# 
## /TESTBED

# EOF=======
