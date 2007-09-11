
class Grid
  
  # helper methods for calculating values for generating the grid.css grid
  
  # calculates the column_width based on the given page_width, margin_width, and number_of_columns.
  # rounds the value off to the nearest full integer value i.e: 48.33333  => 48
  def self.calc_column_width(page_width = nil, margin_width = nil, number_of_columns = nil)
    
    # use the defaults from css.yml if no values are passed in.
    page_width = CssGrid.page_width if page_width.nil? 
    margin_width = CssGrid.margin_width if margin_width.nil? 
    number_of_columns = CssGrid.number_of_columns if number_of_columns.nil? 
    
    # return column width in pixels
    column_width = (((page_width + margin_width) / number_of_columns) - margin_width).round
  end
  
  
  # calculates the page_width based on the given column_width, margin, and number of columns.
  def self.calc_page_width(column_width = nil, margin_width = nil, number_of_columns = nil)
    
    # use the defaults from css.yml if no values are passed in.
    column_width = CssGrid.column_width if column_width.nil? 
    margin_width = CssGrid.margin_width if margin_width.nil? 
    number_of_columns = CssGrid.number_of_columns if number_of_columns.nil? 
    
    # return page width in pixels
    page_width = ((column_width + margin_width) * number_of_columns) - margin_width
  end

end
