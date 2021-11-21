<?php
	
	/*
		class: Template
		parameters: none
		info: creates a template object for editor-based programming
	*/

	class Template {

		// member: template
		// info: the base template string with flags
		private $template = '';

		// member: empty template
		// info: the base template string for creation of empty templates
		private $emptyTemplate = '';

		// member: arguments[]
		// info: an array of argument keys to be substituted for the $this->template printf flags
		private $arguments = array();
		
		/*
			algorithm: Template::start
			parameters: none
			returns: none
			info: starts the template with a <dt> tag, with menu_id and order_key attributes
		*/
		public function start() {
			$this->template .= "<dt class='item'>";
			$this->emptyTemplate .= "<dt class='add'>";
		}

		/*
			algorithm: Template::end
			parameters: none
			returns: none
			info: creates the template <dt> tag
		*/
		public function end() {
			$this->template .= "</dt>";
			$this->emptyTemplate .= "</dt>";
		}

		/*
			algorithm: Template::checkbox
			parameters: none
			returns: none
			info: creates the default #is_active checkbox
		*/
		public function checkbox($id, $label = '') {
			$this->template .= "$label<input type='checkbox' id='$id' %s></input>";
			$this->emptyTemplate .= "$label<input type='checkbox' id='$id'></input>";
			$this->arguments[] = $id;
		}

		/*
			algorithm: Template::entry
			parameters:
				id - the column name of the entry
				[tag] - the html tag used for the entry
					- default: "div"
					- possible values: "div", "code", ...
			returns: none
			info: creates an entry box for a specific column in the data
		*/
		public function entry($id, $tag = 'div') {
			$this->template .= "<$tag class='entry' id='$id'>%s</$tag>";
			$this->emptyTemplate .= "<$tag class='entry' id='$id'></$tag>";
			$this->arguments[] = $id;
		}

		/*
			algorithm: Template::addButton
			parameters: none
			returns: none
			info: creates an editor add-child-element button
		*/
		public function addButton() {
			$button = "<div class='add button'></div>";
			$this->template .= $button;
			$this->emptyTemplate .= $button;
		}

		/*
			algorithm: Template::deleteButton
			parameters: none
			returns: none
			info: creates an editor delete-element button
		*/
		public function deleteButton() {
			$button = "<div class='delete button'></div>";
			$this->template .= $button;
			$this->emptyTemplate .= $button;
		}

		/*
			algorithm: Template::addButton
			parameters: none
			returns: none
			info: creates an editor swap-element button
		*/
		public function swapButton() {
			$button = "<span class='swap'>"
				."<div class='button'></div>"
				."<div class='button'></div>"
				."</span>";
			$this->template .= $button;
			$this->emptyTemplate .= $button;
		}

		/*
			algorithm: Template::process
			parameters:
				data - the key-value paired array that contains the template arguments as keys
			returns: a fully-templated string of data
			info: fills the template with argument values based on given data
		*/
		public function process(&$data) {
			$values = array();
			foreach ($this->arguments as $key) {
				$value = $data[$key];
				if (is_bool($value))
					$values[] = $value ? 'checked' : '';
				else
					$values[] = $value;
			}
			return vsprintf($this->template, $values);
		}

		/*
			algorithm: Template::getEmptyTemplate
			parameters: none
			returns: the contents of the variable emptyTemplate
			info: gets the contents of the variable emptyTemplate
		*/
		function getEmptyTemplate() {
			return $this->emptyTemplate;
		}

	}



?>