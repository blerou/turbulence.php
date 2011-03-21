Hopefully-meaningful Metrics
----------------------------

This work based on [Turbulence](https://github.com/chad/turbulence)

[A quick hack based on Michael Feathers' recent work](http://www.stickyminds.com/sitewide.asp?Function=edetail&ObjectType=COL&ObjectId=16679&tth=DYN&tt=siteemail&iDyn=2) in project churn and complexity

Usage
-----

		bin/turbulence_php -repo=/path/to/git/project -out=/tmp/output

It takes `/path/to/git/project` repository, calculates class/file changes and some kind of complexities, then create an out.json file under `/tmp/output` (it contains the raw data in JSON format).

		bin/turbulence_php -repo=/path/to/git/project -out=/tmp/output -path=src

When `-path` parameter presents only files (classes) under `src/` will be processed.

If everything went well a `viewer.html` will be generated under output (`/tmp/output`). It has no external dependency, so just launch it with your favorite browser.

		google-chrome /tmp/output/viewer.html

Example
-------

Let's create the metrics of Twig template engine:

		pear install pear.pdepend.org/PHP_Depend-beta
		git clone git://github.com/blerou/turbulence.php.git
		git clone git://github.com/fabpot/Twig.git
		turbulence.php/bin/turbulence_php -repo=Twig -out=/tmp/Twig -path=lib
		google-chrome /tmp/Twig/viewer.html


Dependencies
------------

It uses [PDepend](http://pdepend.org/) to calculate complexity.

		pear channel-discover pear.pdepend.org
		pear install pdepend/PHP_Depend-beta

