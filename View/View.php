<?php
namespace View;


class View
{
    protected $config;

    /**
     * @var string PageTitle
     */
    public $title;

    /**
     * @var string page H1 header
     */
    public $h1;

    /**
     * final html
     * @var string
     */
    public $output;

    public function __construct($title)
    {
        if (!is_string($title)
            || empty($title)
        ) {
            $title = "DONE! - ToDo tasks manager";
        }
        $this->title = $title;
        $this->h1 = $title;
        $this->output = '';
    }

    public function render($data)
    {
        $this->content($data);

        $this->body();
        $this->head();
        $this->html();

        echo $this->output;
    }

    /**
     * Render inner content of a page
     * @param mixed $result
     * @return string html string
     */
    protected function content($data)
    {
        $tr = json_encode($data['translations']);
        $cat = json_encode($data['categories']);

        $this->output .= <<<INLINE
<script>var currLang = "en";
		var username = "Guest";
		var trans = JSON.parse($tr);
		var cats = JSON.parse($cat);
</script>

<div ng-app="todo"   class="container">

<div class="row">
	<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">

	</div>
	<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
		{{\$rootScope.username}}
	</div>
	<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
		<div class="btn-group btn-group-xs">
			<label class="btn btn-default"  ng-click="\$rootScope.isCollapsed = !\$rootScope.isCollapsed">CAT</label>
		</div>
		<div class="btn-group btn-group-xs">
			<label class="btn btn-primary"  ng-click="setLang('EN')">EN</label>
			<label class="btn btn-primary"  ng-click="setLang('RU')">RU</label>
		</div>
	</div>
</div>

<div class="row" ng-controller="IoCtrl" ng-hide="\$rootScope.isCollapsed">
	<div class="col-sm-6 pull-right">
		<div class="list-group"  >
			<a href="#" ng-repeat="cats in model" class="list-group-item" ng-class="{'active' : currentShow === cats.name}" ng-click="changeCat(cats.name)" >
			<span class="badge">{{cats.list.length}}</span>
			{{cats.name}}
			</a>
		</div>
	</div>
</div>

<div ng-controller="TodoCtrl" ng-init="init()">

	<div id="playground">


		<div class="row">
					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1  col-md-offset-1 col-lg-offset-1 ">
						<div class="input-group">
							<span class="input-group-btn">
								<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
							</span>
							<input type="search" class="form-control" placeholder="Search" ng-model="todoSearch">
						</div>
					</div>
		</div>

		<div class="row">

			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1  col-md-offset-1 col-lg-offset-1 ">
				<div class="row">
					<ul class="nav nav-pills todo-filter">
						  <li ng-class="{'active' : show == 'Incomplete' }" ng-click="show='Incomplete'"><a href="#">TO DO</a></li>
						  <li ng-class="{'active' : show == 'Complete' }" ng-click="show='Complete'"><a href="#">DONE</a></li>
						  <li ng-class="{'active' : show == 'All' }" ng-click="show='All'"><a href="#">ALL</a></li>
					</ul>
				</div>
			</div>

		</div>

		<div class="row">
			<div id="todoAdd" class="ccol-xs-10 col-sm-10 col-md-10 col-lg-10 col-xs-offset-1 col-sm-offset-1  col-md-offset-1 col-lg-offset-1 ">
				<div class="input-group  input-group-lg">
				  <input type="text" class="form-control" ng-model="newTodo"  placeholder="Add New Task" aria-describedby="basic-addon2" ng-enter>
				  <span class="input-group-addon" id="basic-addon2" ng-click="addTodo()">{{tr('ADD')}}</span>
				</div>
			</div>
		</div>


	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="row">
					<ul class="list-unstyled" ng-repeat="todos in model track by \$index" ui-sortable="todoSortable" ng-model="todos.list" ng-show="\$index === currentShow">
						<li class="todoTask" ng-repeat="todo in todos.list | filter:showFn | filter :todoSearch ">
							<input class="todoCheckbox" ng-model="todo.isDone" type="checkbox">
							<label class="todoCheckboxlabel" ></label>
							<edit-in-place value="todo.header"></edit-in-place>
							<button type="button" class="close pull-right" aria-hidden="true" ng-click="deleteTodo(\$index)">&times;</button>
						</li>
					</ul>
				</div>
	</div>



		<div class="row">
			<div class="debug">
				<p class="text-info">{{ model | json}}</p>
			</div>
		</div>
	</div>
</div>
</div>
;
INLINE;
    }

    // decorate content with body tags
    protected function body ($data=[])
    {
        $h1 = (isset($data['h1'])) ? htmlspecialchars($data['h1']) : $this->h1;
        $this->output = '<body><div class="container"><div class="row"><div class="col-sm-10"><h1>'.$h1.'</h1></div></div></div>'.$this->output.'</body>';
    }

    protected function head()
    {
        $this->output = <<<HEAD
<head>
    <meta charset="utf-8">
    <title>$this->title</title>
    <meta name="author" content="TODay <otodwebdev@gmail.com>" />
    <link rel="stylesheet" href="/todo/css/style_done.css" type="text/css">
    <link rel="stylesheet" href="/todo/css/bootstrap.min.css">
	<link rel="stylesheet" href="/todo/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/todo/css/common.css">

	<script src="/todo/js/libs/jquery.min.js"></script>
	<script src="/todo/js/libs/jquery-ui.min.js"></script>
	<script src="/todo/js/libs/bootstrap.min.js"></script>
	<script src="/todo/js/libs/angular.min.js"></script>
	<script src="/todo/js/libs/angular-local-storage.min.js"></script>
	<script src="/todo/js/libs/sortable.js"></script>
	<script src="/todo/js/app.js"></script>
	<script src="/todo/js/directives/angular.editInPlace.js"></script>
	<script src="/todo/js/directives/angular.ngEnter.js"></script>


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>$this->output
HEAD;

    }

    protected function html()
    {
        $this->output = <<<HTML
<!DOCTYPE html>
<html lang="en"  ng-app="done">
$this->output
</html>
HTML;

    }

    protected function error($text)
    {
        $this->output .= '<h3>'.htmlspecialchars($text).'</h3>';
    }

    protected function h2($text)
    {
        $this->output .= '<h2>'.$text.'</h2>';
    }

}