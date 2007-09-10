require File.dirname(__FILE__) + '/../test_helper'
require 'rcss_controller'

# Re-raise errors caught by the controller.
class RcssController; def rescue_action(e) raise e end; end

class RcssControllerTest < Test::Unit::TestCase
  
  def setup
    @controller = RcssController.new
    @request    = ActionController::TestRequest.new
    @response   = ActionController::TestResponse.new
  end
  
  # Replace this with your real tests.
  def test_truth
    assert true
  end
  
  # call rcss method in rcss controler with "test.css" testing file
  # test.rcss file will be called and should return in the body
  # a line of dynamically generated text: ".Stylefile test.rcss"
  def test_testRCSS
    get :rcss, {:rcssfile => 'test.css'}
    #ensure local variable names stylesheet correctly
    assert_equal assigns(:stylefile), '/rcss/test.rcss'
    #ensure content-type is text/css
    assert_match @response.headers['Content-Type'], 'text/css; charset=utf-8'
    #ensure we get a successful response
    assert_response :success
    #ensure we process using correct rcss template
    assert_template '/rcss/test.rcss'
    #test for specific dynamic content in test.rcss page
    assert_match '.Stylefile /rcss/test.rcss', @response.body
    #ensure that the map routing is working correctly
    assert_routing '/rcss/test.css', {:controller => 'rcss', :action => 'rcss', :rcssfile => 'test', :format => 'css'}
  end
  
end
