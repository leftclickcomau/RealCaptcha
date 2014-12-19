RealCaptcha
===========

RealCaptcha is a captcha mechanism which is easy to use and adds real human verification to your site.

Like most captcha mechanisms, it works by displaying a generated image which represents a code, and storing an
associated required result in the session.  This result may or may not be the same as the code itself, depending on the
code generator being used.  The user's input can be verified simply by comparing the value submitted in the request
against the value stored in the session.

The image is decorated with configurable noise in order to prevent recognition by automatic agents.

Code Generators
---------------

The code generation process is extensible.  An interface is provided which a class must implement to be recognised as
a code generator.  An abstract implementation of this interface provides a useful starting point.

Two code generator implementations are provided:

* Alphanumeric creates a code using a sequence of random characters, and expects the user to enter this code.
* Mathematical creates a simple mathematical equation using addition, multiplication and subtraction, and expects the
  user to enter the answer to the equation.

As shown in the "dynamic" demonstration page, the code generator can be changed on the fly by passing a URL parameter.

Image Configuration
-------------------

Several aspects of the image itself can be configured by setting options, including the size of the image, the font
used to render the characters, the font size in relation to the image, the rotation range of the code, the colours (or
colour ranges) used for the various layers, the image format (png, jpeg or gif), etc.

Layer Renderers
---------------

The image is made up of layers, including the code itself and the layers of noise.  These layers can be stacked in any
order to provide different effects (i.e. noise over or under the code itself, or both).  Layers can be used many times
in the rendering sequence, except the code layer which should only be used once.

The layer renderer mechanism is also extensible through an interface and abstract class.  For example, images could be
added in the background.

Graphics Engines
----------------

The graphics layer, which handles the low-level drawing operations, is also abstract.  Currently, the only 
implementation that is packaged with RealCaptcha uses the GD library for its drawing operations.  Other implementations
can be easily added, e.g. utilising the ImageMagick library.

No Reload Button?
-----------------

No reload button is generated by the code here.  Instead, the demo section contains some example code that provides a
reload button.  All that is required is to update the URL of the image with a new random number.  By not having this
built in to the library, it provides full flexibility for integrating with the look and feel of the website, form
layout, etc.

Composer
--------

    "require": {
        //...
        "leftclickcomau/real-captcha": "*"
    }

Unit Tests
----------

To run the unit tests, from the project root:

1. composer install --dev
2. phpunit --bootstrap=vendor/autoload.php tests/unit
