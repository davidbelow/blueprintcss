class GeneratorController < ApplicationController
  
  def index
  end
  
  def set_grid_default
    # if the session[:grid] is nil we should ignore any custom settings when we generate
    session[:grid] = nil
    session[:grid_number_of_columns] = nil
    session[:grid_column_width] = nil
    session[:grid_margin_width] = nil
    
    render :update do |page|
      page["grid_status"].replace_html "default"
      page["grid_status"].visual_effect(:highlight, :duration => 2)
      page["step_grid"].visual_effect(:fade)
    end
  end
  
  def set_grid_custom
    # if the session[:grid] is 'custom' we need to use the custom settings when generating
    session[:grid] = 'custom'
    session[:grid_number_of_columns] = CssGrid.number_of_columns
    session[:grid_column_width] = CssGrid.column_width
    session[:grid_margin_width] = CssGrid.margin_width
    
    render :update do |page|
      page["grid_status"].replace_html "custom"
      page["grid_status"].visual_effect(:highlight, :duration => 2)
      page.visual_effect(:toggle_slide,"step_grid")
      # page["step_grid_form"].replace_html :partial => "step_grid_form"
     
      # page["step_grid_form"].visual_effect(:highlight, :duration => 2)
    end
  end
  
  def set_grid_remove
    # if the session[:grid] is 'remove' we need to ignore custom settings and not even include the grid when generating
    session[:grid] = 'remove'
    session[:grid_number_of_columns] = nil
    session[:grid_column_width] = nil
    session[:grid_margin_width] = nil
    
    render :update do |page|
      page["grid_status"].replace_html "removed"
      page["grid_status"].visual_effect(:highlight, :duration => 2)
      page["step_grid"].visual_effect(:fade)
    end
  end
  
  def save_grid_custom_params
    
    # save custom params to the session as they come in:
    session[:grid_number_of_columns] = params[:number_of_columns] unless params[:number_of_columns].nil?
    session[:grid_column_width] = params[:column_width] unless params[:column_width].nil?
    session[:grid_margin_width] = params[:margin_width] unless params[:margin_width].nil?
    
    # calculate the new page width
    page_width = Grid.calc_page_width(session[:grid_column_width].to_i, session[:grid_margin_width].to_i, session[:grid_number_of_columns].to_i )
    
    render :update do |page|
      page["grid_page_width"].replace_html page_width.to_s
    end
    
  end
  
end
