Hopefully-meaningful Metrics
----------------------------

This work based on [Turbulence](https://github.com/chad/turbulence)

[A quick hack based on Michael Feathers' recent work](http://www.stickyminds.com/sitewide.asp?Function=edetail&ObjectType=COL&ObjectId=16679&tth=DYN&tt=siteemail&iDyn=2) in project churn and complexity

Usage
-----

		bin/turbulence -r=/path/to/git/project -o=/tmp/output

It takes `/path/to/git/project` repository, calculates churn and complexity, then create an out.json file under `/tmp/output`.

		bin/turbulence -r=/path/to/git/project -o=/tmp/output -v

When `-v` parameter presents it creates a "viewable" html reprezentation of the metrics under `/tmp/output/viewer.html`.

		bin/turbulence -r=/path/to/git/project -o=/tmp/output -v=firefox

When `-v` parameter value setted it opens the `viewer.html` by `firefox` in this case.

Dependencies
------------

It uses [PDepend](http://pdepend.org/) to calculate complexity.

		pear channel-discover pear.pdepend.org
		pear install pdepend/PHP_Depend-beta

