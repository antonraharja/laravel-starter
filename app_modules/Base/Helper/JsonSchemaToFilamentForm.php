<?php

namespace Base\Helper;

use Filament\Forms\Components\{
	CheckboxList,
	ColorPicker,
	DatePicker,
	DateTimePicker,
	FileUpload,
	Grid,
	Group,
	Hidden,
	Radio,
	Repeater,
	RichEditor,
	Section,
	Select,
	Tabs,
	Textarea,
	TextInput,
	TimePicker,
	Toggle,
	KeyValue,
	MarkdownEditor,
	TagsInput,
	View,
	Fieldset,
	Placeholder,
	Actions\Action
};

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Helper class to convert JSON schemas to Filament form components
 * 
 * @package App\Helpers
 */
class JsonSchemaToFilamentForm
{
	/**
	 * Convert a JSON schema to a Filament form
	 *
	 * @param string|array $schema JSON schema as string or array
	 * @return array<int, mixed> Form components
	 * @throws InvalidArgumentException When schema is invalid
	 */
	public function convert(string|array $schema): array
	{
		$schema = $this->parseSchema($schema);
		return $this->processSchema($schema);
	}

	/**
	 * Parse and validate the schema
	 *
	 * @param string|array $schema JSON schema to parse
	 * @return array The parsed schema as array
	 * @throws InvalidArgumentException When schema is invalid
	 */
	protected function parseSchema(string|array $schema): array
	{
		if (is_string($schema)) {
			$schema = json_decode($schema, true, 512, JSON_THROW_ON_ERROR);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new InvalidArgumentException('Invalid JSON schema');
			}
		}

		if (!is_array($schema)) {
			throw new InvalidArgumentException('Schema must be a valid JSON string or array');
		}

		return $schema;
	}

	/**
	 * Process the schema and generate form components
	 *
	 * @param array $schema The schema to process
	 * @return array<int, mixed> The generated form components
	 */
	protected function processSchema(array $schema): array
	{
		return isset($schema['layout'])
			? $this->processLayout($schema['layout'], $schema['fields'] ?? [])
			: $this->processFields($schema['fields'] ?? []);
	}

	/**
	 * Process fields directly without layout
	 *
	 * @param array $fields The fields to process
	 * @return array<int, mixed> The generated form components
	 */
	protected function processFields(array $fields): array
	{
		return array_map(
			fn(array $field) => $this->createComponent($field),
			array_filter($fields, fn($field) => is_array($field) && isset($field['type']))
		);
	}

	/**
	 * Process layout structure (tabs, sections, grid)
	 *
	 * @param array $layout The layout structure
	 * @param array $fields Available fields to include in layout
	 * @return array<int, mixed> The generated layout components
	 */
	protected function processLayout(array $layout, array $fields): array
	{
		return array_filter(array_map(
			function (array $item) use ($fields) {
				if (!isset($item['type'])) {
					return null;
				}

				return match ($item['type']) {
					'tabs' => $this->createTabs($item, $fields),
					'section' => $this->createSection($item, $fields),
					'grid' => $this->createGrid($item, $fields),
					'group' => $this->createGroup($item, $fields),
					'fieldset' => $this->createFieldset($item, $fields),
					default => null,
				};
			},
			$layout
		));
	}

	/**
	 * Find field definition by name
	 *
	 * @param string $name The field name to find
	 * @param array $fields Available fields to search in
	 * @return array|null The field definition if found, null otherwise
	 */
	protected function findField(string $name, array $fields): ?array
	{
		foreach ( $fields as $field ) {
			if (isset($field['name']) && $field['name'] === $name) {
				return $field;
			}
		}

		return null;
	}

	/**
	 * Create a form component based on field definition
	 *
	 * @param array $field The field definition
	 * @return mixed The created component
	 * @throws InvalidArgumentException When field type is missing or unsupported
	 */
	protected function createComponent(array $field)
	{
		if (!isset($field['type'])) {
			throw new InvalidArgumentException("Field type is required");
		}

		// Validate field name to prevent XSS
		if (isset($field['name']) && !preg_match('/^[a-zA-Z0-9_-]+$/', $field['name'])) {
			throw new InvalidArgumentException("Invalid field name format");
		}

		$type = $field['type'];
		$method = 'create' . Str::studly($type) . 'Field';

		if (!method_exists($this, $method)) {
			throw new InvalidArgumentException("Unsupported field type: {$type}");
		}

		return $this->$method($field);
	}

	/**
	 * Create text input field
	 * 
	 * @param array $field The field definition
	 * @return TextInput The created text input component
	 */
	protected function createTextField(array $field): TextInput
	{
		$component = TextInput::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['maxLength'])) {
			$component->maxLength((int) $field['maxLength']);
		}

		if (isset($field['minLength'])) {
			$component->minLength((int) $field['minLength']);
		}

		if (isset($field['pattern'])) {
			$component->regex($field['pattern']);
		}

		if (isset($field['inputType'])) {
			$component->type($field['inputType']);
		}

		return $component;
	}

	/**
	 * Create password input field
	 * 
	 * @param array $field The field definition
	 * @return TextInput The created password input component
	 */
	protected function createPasswordField(array $field): TextInput
	{
		$component = $this->createTextField($field);

		$component->password();

		if (isset($field['revealable']) && !$field['revealable']) {
			$component->revealable(false);
		}

		return $component;
	}

	/**
	 * Create textarea field
	 * 
	 * @param array $field The field definition
	 * @return Textarea The created textarea component
	 */
	protected function createTextareaField(array $field): Textarea
	{
		$component = Textarea::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['rows'])) {
			$component->rows((int) $field['rows']);
		}

		if (isset($field['autosize']) && $field['autosize']) {
			$component->autosize();
		}

		return $component;
	}

	/**
	 * Create rich editor field
	 * 
	 * @param array $field The field definition
	 * @return RichEditor The created rich editor component
	 */
	protected function createRichEditorField(array $field): RichEditor
	{
		$component = RichEditor::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['toolbarButtons'])) {
			$component->toolbarButtons($field['toolbarButtons']);
		}

		return $component;
	}

	/**
	 * Create markdown editor field
	 * 
	 * @param array $field The field definition
	 * @return MarkdownEditor The created markdown editor component
	 */
	protected function createMarkdownField(array $field): MarkdownEditor
	{
		$component = MarkdownEditor::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);
		return $component;
	}

	/**
	 * Create select field
	 * 
	 * @param array $field The field definition
	 * @return Select The created select component
	 */
	protected function createSelectField(array $field): Select
	{
		$component = Select::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['options']) && is_array($field['options'])) {
			$component->options($this->processOptions($field['options']));
		}

		if (isset($field['enum']) && enum_exists($field['enum'])) {
			$component->options($field['enum']::cases());
		}

		if (isset($field['multiple']) && $field['multiple']) {
			$component->multiple();
		}

		if (isset($field['searchable']) && $field['searchable']) {
			$component->searchable();
		}

		if (isset($field['preload']) && $field['preload']) {
			$component->preload();
		}

		return $component;
	}

	/**
	 * Create toggle/switch field
	 * 
	 * @param array $field The field definition
	 * @return Toggle The created toggle component
	 */
	protected function createToggleField(array $field): Toggle
	{
		$component = Toggle::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['onColor'])) {
			$component->onColor($field['onColor']);
		}

		if (isset($field['offColor'])) {
			$component->offColor($field['offColor']);
		}

		return $component;
	}

	/**
	 * Create checkbox list field
	 * 
	 * @param array $field The field definition
	 * @return CheckboxList The created checkbox list component
	 */
	protected function createCheckboxListField(array $field): CheckboxList
	{
		$component = CheckboxList::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['options']) && is_array($field['options'])) {
			$component->options($this->processOptions($field['options']));
		}

		return $component;
	}

	/**
	 * Create radio button field
	 * 
	 * @param array $field The field definition
	 * @return Radio The created radio button component
	 */
	protected function createRadioField(array $field): Radio
	{
		$component = Radio::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['options']) && is_array($field['options'])) {
			$component->options($this->processOptions($field['options']));
		}

		return $component;
	}

	/**
	 * Create date picker field
	 * 
	 * @param array $field The field definition
	 * @return DatePicker The created date picker component
	 */
	protected function createDateField(array $field): DatePicker
	{
		$component = DatePicker::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['format'])) {
			$component->format($field['format']);
		}

		if (isset($field['minDate'])) {
			$component->minDate($field['minDate']);
		}

		if (isset($field['maxDate'])) {
			$component->maxDate($field['maxDate']);
		}

		if (isset($field['displayFormat'])) {
			$component->displayFormat($field['displayFormat']);
		}

		return $component;
	}

	/**
	 * Create time picker field
	 * 
	 * @param array $field The field definition
	 * @return TimePicker The created time picker component
	 */
	protected function createTimeField(array $field): TimePicker
	{
		$component = TimePicker::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['format'])) {
			$component->format($field['format']);
		}

		if (isset($field['interval'])) {
			$component->minutesStep($field['interval']);
		}

		return $component;
	}

	/**
	 * Create datetime picker field
	 * 
	 * @param array $field The field definition
	 * @return DateTimePicker The created datetime picker component
	 */
	protected function createDatetimeField(array $field): DateTimePicker
	{
		$component = DateTimePicker::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['format'])) {
			$component->format($field['format']);
		}

		if (isset($field['minDate'])) {
			$component->minDate($field['minDate']);
		}

		if (isset($field['maxDate'])) {
			$component->maxDate($field['maxDate']);
		}

		if (isset($field['displayFormat'])) {
			$component->displayFormat($field['displayFormat']);
		}

		return $component;
	}

	/**
	 * Create file upload field
	 * 
	 * @param array $field The field definition
	 * @return FileUpload The created file upload component
	 */
	protected function createFileField(array $field): FileUpload
	{
		$component = FileUpload::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		// Add default disk if not specified
		if (!isset($field['disk'])) {
			$component->disk('local'); // or your default secure disk
		} else {
			$component->disk($field['disk']);
		}

		if (isset($field['directory'])) {
			$component->directory($field['directory']);
		}

		if (isset($field['acceptedFileTypes']) && is_array($field['acceptedFileTypes'])) {
			$component->acceptedFileTypes($field['acceptedFileTypes']);
		}

		if (isset($field['maxSize'])) {
			$component->maxSize((int) $field['maxSize']);
		}

		if (isset($field['multiple']) && $field['multiple']) {
			$component->multiple();
		}

		if (isset($field['image']) && $field['image']) {
			$component->image();
		}

		if (isset($field['imagePreviewHeight'])) {
			$component->imagePreviewHeight($field['imagePreviewHeight']);
		}

		if (isset($field['imageResizeTargetWidth'])) {
			$component->imageResizeTargetWidth($field['imageResizeTargetWidth']);
		}

		if (isset($field['imageResizeTargetHeight'])) {
			$component->imageResizeTargetHeight($field['imageResizeTargetHeight']);
		}

		return $component;
	}

	/**
	 * Create color picker field
	 * 
	 * @param array $field The field definition
	 * @return ColorPicker The created color picker component
	 */
	protected function createColorField(array $field): ColorPicker
	{
		$component = ColorPicker::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['swatches']) && is_array($field['swatches'])) {
			$component->swatches($field['swatches']);
		}

		return $component;
	}

	/**
	 * Create hidden field
	 * 
	 * @param array $field The field definition
	 * @return Hidden The created hidden field component
	 */
	protected function createHiddenField(array $field): Hidden
	{
		$component = Hidden::make($field['name']);

		if (isset($field['default'])) {
			$component->default($field['default']);
		}

		return $component;
	}

	/**
	 * Create repeater field
	 * 
	 * @param array $field The field definition
	 * @return Repeater The created repeater component
	 */
	protected function createRepeaterField(array $field): Repeater
	{
		$component = Repeater::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['schema']) && is_array($field['schema'])) {
			$component->schema(
				array_map(
					fn(array $schemaField) => $this->createComponent($schemaField),
					$field['schema']
				)
			);
		}

		if (isset($field['minItems'])) {
			$component->minItems((int) $field['minItems']);
		}

		if (isset($field['maxItems'])) {
			$component->maxItems((int) $field['maxItems']);
		}

		if (isset($field['itemLabel']) && is_string($field['itemLabel'])) {
			$component->itemLabel($field['itemLabel']);
		}

		if (isset($field['collapsible']) && $field['collapsible']) {
			$component->collapsible();
		}

		if (isset($field['cloneable']) && $field['cloneable']) {
			$component->cloneable();
		}

		return $component;
	}

	/**
	 * Create key-value field
	 * 
	 * @param array $field The field definition
	 * @return KeyValue The created key-value component
	 */
	protected function createKeyValueField(array $field): KeyValue
	{
		$component = KeyValue::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['keyLabel'])) {
			$component->keyLabel($field['keyLabel']);
		}

		if (isset($field['valueLabel'])) {
			$component->valueLabel($field['valueLabel']);
		}

		return $component;
	}

	/**
	 * Create tags input field
	 * 
	 * @param array $field The field definition
	 * @return TagsInput The created tags input component
	 */
	protected function createTagsField(array $field): TagsInput
	{
		$component = TagsInput::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		$this->applyCommonFieldProperties($component, $field);

		if (isset($field['splitKeys'])) {
			$component->splitKeys($field['splitKeys']);
		}

		return $component;
	}

	/**
	 * Create view component
	 * 
	 * @param array $field The field definition
	 * @return View The created view component
	 * @throws InvalidArgumentException When view property is missing or invalid
	 */
	protected function createViewField(array $field): View
	{
		if (!isset($field['view'])) {
			throw new InvalidArgumentException("View field requires a 'view' property");
		}

		// Validate view path to prevent directory traversal
		if (str_contains($field['view'], '..') || !Str::startsWith($field['view'], 'filament.')) {
			throw new InvalidArgumentException("Invalid view path");
		}

		$component = View::make($field['view']);

		if (isset($field['data']) && is_array($field['data'])) {
			$component->data($field['data']);
		}

		return $component;
	}

	/**
	 * Create placeholder component
	 * 
	 * @param array $field The field definition
	 * @return Placeholder The created placeholder component
	 */
	protected function createPlaceholderField(array $field): Placeholder
	{
		$component = Placeholder::make($field['name'] ?? Str::random());

		if (isset($field['content'])) {
			$component->content($field['content']);
		}

		if (isset($field['label'])) {
			$component->label($field['label']);
		}

		return $component;
	}

	/**
	 * Create fieldset component
	 * 
	 * @param array $fieldset The fieldset definition
	 * @param array $fields Available fields to include in fieldset
	 * @return Fieldset The created fieldset component
	 */
	protected function createFieldset(array $fieldset, array $fields): Fieldset
	{
		$component = Fieldset::make($fieldset['label'] ?? '');

		if (isset($fieldset['fields']) && is_array($fieldset['fields'])) {
			$component->schema($this->getFieldsSchema($fieldset['fields'], $fields));
		}

		return $component;
	}

	/**
	 * Create section component
	 * 
	 * @param array $section The section definition
	 * @param array $fields Available fields to include in section
	 * @return Section The created section component
	 */
	protected function createSection(array $section, array $fields): Section
	{
		$component = Section::make($section['title'] ?? '');

		if (isset($section['description'])) {
			$component->description($section['description']);
		}

		if (isset($section['columns'])) {
			$component->columns((int) $section['columns']);
		}

		if (isset($section['collapsible']) && $section['collapsible']) {
			$component->collapsible();
		}

		if (isset($section['collapsed']) && $section['collapsed']) {
			$component->collapsed();
		}

		if (isset($section['aside']) && $section['aside']) {
			$component->aside();
		}

		if (isset($section['fields']) && is_array($section['fields'])) {
			$component->schema($this->getFieldsSchema($section['fields'], $fields));
		}

		return $component;
	}

	/**
	 * Create tabs component
	 * 
	 * @param array $tabs The tabs definition
	 * @param array $fields Available fields to include in tabs
	 * @return Tabs The created tabs component
	 */
	protected function createTabs(array $tabs, array $fields): Tabs
	{
		$component = Tabs::make($tabs['id'] ?? 'tabs');

		if (isset($tabs['tabs']) && is_array($tabs['tabs'])) {
			$component->tabs(array_map(
				function (array $tab) use ($fields) {
					if (!isset($tab['label'])) {
						return null;
					}

					return Tabs\Tab::make($tab['id'] ?? Str::slug($tab['label']))
						->label($tab['label'])
						->schema(
							isset($tab['fields']) && is_array($tab['fields'])
							? $this->getFieldsSchema($tab['fields'], $fields)
							: []
						);
				},
				array_filter($tabs['tabs'], fn($tab) => is_array($tab))
			));
		}

		return $component;
	}

	/**
	 * Create grid component
	 * 
	 * @param array $grid The grid definition
	 * @param array $fields Available fields to include in grid
	 * @return Grid The created grid component
	 */
	protected function createGrid(array $grid, array $fields): Grid
	{
		$component = Grid::make();

		if (isset($grid['columns'])) {
			$component->columns((int) $grid['columns']);
		}

		if (isset($grid['fields']) && is_array($grid['fields'])) {
			$component->schema($this->getFieldsSchema($grid['fields'], $fields));
		}

		return $component;
	}

	/**
	 * Create group component
	 * 
	 * @param array $group The group definition
	 * @param array $fields Available fields to include in group
	 * @return Group The created group component
	 */
	protected function createGroup(array $group, array $fields): Group
	{
		$component = Group::make();

		if (isset($group['fields']) && is_array($group['fields'])) {
			$component->schema($this->getFieldsSchema($group['fields'], $fields));
		}

		if (isset($group['inline']) && $group['inline']) {
			$component->inline();
		}

		return $component;
	}

	/**
	 * Create action component
	 * 
	 * @param array $field The field definition
	 * @return Action The created button component
	 * @throws InvalidArgumentException When required properties are missing
	 */
	protected function createActionField(array $field): Action
	{
		if (!isset($field['name'])) {
			throw new InvalidArgumentException("Action requires a 'name' property");
		}

		$component = Action::make($field['name'])
			->label($field['label'] ?? Str::headline($field['name']));

		if (isset($field['color'])) {
			$component->color($field['color']);
		}

		if (isset($field['size'])) {
			$component->size($field['size']);
		}

		if (isset($field['icon'])) {
			$component->icon($field['icon']);
		}

		if (isset($field['action'])) {
			$component->action($field['action']);
		}

		if (isset($field['url'])) {
			$component->url($field['url']);
		}

		if (isset($field['outlined']) && $field['outlined']) {
			$component->outlined();
		}

		if (isset($field['extraAttributes']) && is_array($field['extraAttributes'])) {
			$component->extraAttributes($field['extraAttributes']);
		}

		if (isset($field['disabled']) && $field['disabled']) {
			$component->disabled();
		}

		return $component;
	}

	/**
	 * Create button component
	 * 
	 * @param array $field The field definition
	 * @return Action The created button component
	 */
	protected function createButtonField(array $field): Action
	{
		$component = $this->createActionField($field);
		$component->button();

		return $component;
	}

	/**
	 * Create link component
	 * 
	 * @param array $field The field definition
	 * @return Action The created link component
	 */
	protected function createLinkField(array $field): Action
	{
		$component = $this->createActionField($field);
		$component->link();

		return $component;
	}

	/**
	 * Get schema for multiple fields
	 * 
	 * @param array $fieldNames Names of fields to include
	 * @param array $fields Available field definitions
	 * @return array<int, mixed> Array of form components for the specified fields
	 */
	protected function getFieldsSchema(array $fieldNames, array $fields): array
	{
		return array_filter(array_map(
			fn(string $fieldName) => ($field = $this->findField($fieldName, $fields))
			? $this->createComponent($field)
			: null,
			$fieldNames
		));
	}

	/**
	 * Process options for select/checkbox/radio fields
	 * 
	 * @param array $options Raw options from the schema
	 * @return array<string, string> Processed options with value => label pairs
	 */
	protected function processOptions(array $options): array
	{
		return array_reduce($options, function (array $carry, $option) {
			if (is_array($option) && isset($option['value'], $option['label'])) {
				$carry[$option['value']] = $option['label'];
			} elseif (is_string($option)) {
				$carry[$option] = Str::headline($option);
			}
			return $carry;
		}, []);
	}

	/**
	 * Apply common field properties to a component
	 * 
	 * @param \Filament\Forms\Components\Field $component The component to modify
	 * @param array $field The field definition containing properties
	 * @return void
	 */
	protected function applyCommonFieldProperties(
		\Filament\Forms\Components\Field $component,
		array $field
	): void {
		if (isset($field['placeholder'])) {
			$component->placeholder($field['placeholder']);
		}

		if (isset($field['help'])) {
			$component->helperText($field['help']);
		}

		if (isset($field['default'])) {
			$component->default($field['default']);
		}

		if (isset($field['required']) && $field['required']) {
			$component->required();
		}

		if (isset($field['disabled']) && $field['disabled']) {
			$component->disabled();
		}

		if (isset($field['hidden']) && $field['hidden']) {
			$component->hidden();
		}

		if (isset($field['autofocus']) && $field['autofocus']) {
			$component->autofocus();
		}

		if (isset($field['autocomplete'])) {
			$component->autocomplete($field['autocomplete']);
		}

		if (isset($field['extraAttributes']) && is_array($field['extraAttributes'])) {
			$component->extraAttributes($field['extraAttributes']);
		}

		if (isset($field['columnSpan'])) {
			$component->columnSpan($field['columnSpan']);
		}

		if (isset($field['columns']) && $component instanceof \Filament\Forms\Components\Concerns\HasColumns) {
			$component->columns($field['columns']);
		}

		if (isset($field['validation']) && is_array($field['validation'])) {
			$component->rules($field['validation']);
		}
	}
}
