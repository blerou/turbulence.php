Hopefully-meaningful Metrics
----------------------------

This work based on [Turbulence](Based on https://github.com/chad/turbulence)

[A quick hack based on Michael Feathers' recent work](http://www.stickyminds.com/sitewide.asp?Function=edetail&ObjectType=COL&ObjectId=16679&tth=DYN&tt=siteemail&iDyn=2) in project churn and complexity

Usage
-----

		bin/chuggle -r=/path/to/git/project -o=/tmp/output -v=firefox

it takes `/path/to/git/project` repository, calculates chunks and complexities and create an out.json file under `/tmp/output`, the creates a "viewable" html reprezentation of the metrics under `/tmp/output/viewer.html` and opens it by `firefox`.


Dependencies
------------

It uses [PDepend](http://pdepend.org/) to calculate complexity.

		pear channel-discover pear.pdepend.org
		pear install pdepend/PHP_Depend-beta

