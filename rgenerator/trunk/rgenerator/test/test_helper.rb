ENV["RAILS_ENV"] = "test"
require File.expand_path(File.dirname(__FILE__) + "/../config/environment")
require 'test_help'

# load the test-spec gem for BDD testing
require 'test/spec'

# load the test_spec_on_rails plugin
# http://svn.techno-weenie.net/projects/plugins/test_spec_on_rails/README
require 'test/spec/rails'

class Test::Unit::TestCase
  # Transactional fixtures accelerate your tests by wrapping each test method
  # in a transaction that's rolled back on completion.  This ensures that the
  # test database remains unchanged so your fixtures don't have to be reloaded
  # between every test method.  Fewer database queries means faster tests.
  #
  # Read Mike Clark's excellent walkthrough at
  #   http://clarkware.com/cgi/blosxom/2005/10/24#Rails10FastTesting
  #
  # Every Active Record database supports transactions except MyISAM tables
  # in MySQL.  Turn off transactional fixtures in this case; however, if you
  # don't care one way or the other, switching from MyISAM to InnoDB tables
  # is recommended.
  self.use_transactional_fixtures = true

  # Instantiated fixtures are slow, but give you @david where otherwise you
  # would need people(:david).  If you don't want to migrate your existing
  # test cases which use the @david style and don't mind the speed hit (each
  # instantiated fixtures translates to a database query per test method),
  # then set this back to true.
  self.use_instantiated_fixtures  = false

  # Add more helper methods to be used by all tests here...
  
  # Test/Spec for Rails
  # See : http://require.errtheblog.com/plugins/wiki/TestSpecRails
  require 'test/spec/rails'
  
  # allow us to do some mocking goodness with test/spec/rails
  require 'mocha'
  
  # Some test/spec/rails enhancements
  Test::Spec::Should.send    :alias_method, :have, :be
  Test::Spec::ShouldNot.send :alias_method, :have, :be
  
  Test::Spec::Should.class_eval do
    # Article.should.differ(:count).by(2) { blah } 
    def differ(method)
      @initial_value = @object.send(@method = method)
      self
    end
    
    def by(value)
      yield
      assert_equal @initial_value + value, @object.send(@method)
    end
  end
  
end
