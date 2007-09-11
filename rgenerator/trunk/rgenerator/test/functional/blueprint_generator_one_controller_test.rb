require File.dirname(__FILE__) + '/../test_helper'
require 'blueprint_generator_one_controller'

# Re-raise errors caught by the controller.
class BlueprintGeneratorOneController; def rescue_action(e) raise e end; end

class BlueprintGeneratorOneControllerTest < Test::Unit::TestCase
  def setup
    @controller = BlueprintGeneratorOneController.new
    @request    = ActionController::TestRequest.new
    @response   = ActionController::TestResponse.new
  end

  # Replace this with your real tests.
  def test_truth
    assert true
  end
end
