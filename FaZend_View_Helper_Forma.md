# Sample Usage #

Your model class:

```
class Model_Person
{
    public static function create($name, $sex, $sendEmail, $resume, Model_File $photo)
    {
        validate()
            ->type($sex, 'boolean', "SEX should be BOOL (TRUE=male, FALSE=female")
            ->regex($name, '/^[a-zA-Z\s]+$/', "Invalid name format");
        // ...
    }
}
```

Right inside your view script:

```
<?=$this->forma(1) // this id will distinguish many forms on one page
    ->addBehavior(
        'flash', 
        '
        sprintf(
            "Account registered: %s (%d bytes in resume)", 
            ${a1}, 
            strlen(strval(${a4}))
        )
        '
    ) // set flashMessage message
    ->addBehavior('redirect', 'registered', 'index') // will be forwarded to /index/registered
    ->addAttrib('id', 'form13')
    ->addField('text', 'name')
        ->fieldLabel('Your name')
        ->fieldRequired(true)
        ->fieldAttrib('maxlength', 45)
        ->fieldConverter('new Model_Name(${a1})') // convert field into Model_Name
    ->addField('select', 'sex')
        ->fieldLabel('Your sex')
        ->fieldOptions(array(0=>'female', 1=>'male'))
        ->fieldConverter('boolean') // convert this field into BOOLEAN
    ->addField('hidden', 'sendEmail')
        ->fieldValue(true)
    ->addField('textarea', 'resume')
        ->fieldAttrib('cols', 50)
        ->fieldAttrib('rows', 5)
    ->addField('file', 'photo')
        ->fieldConverter('Model_File::factory(${a1})') // convert it by means of Model_File::factory()
    ->addField('submit')
        ->fieldAction(array('Model_Person', 'create')) // call Model_Person::create(), when the form is filled
    ?>
```

That's it. The form will execute your specified method. And parameters from the form will be passed to the method specified (`Model_Person::create`).

The benefits you get:

  * Majority of forms don't need controllers, since they are simple
  * Validation could be done in model class, throwing exceptions out - they will be converted into form error messages
  * You don't need INI files for form creation and in general less text to type