Hopefully-meaningful Metrics
----------------------------

This work based on [Turbulence](https://github.com/chad/turbulence)

[A quick hack based on Michael Feathers' recent work](http://www.stickyminds.com/sitewide.asp?Function=edetail&ObjectType=COL&ObjectId=16679&tth=DYN&tt=siteemail&iDyn=2) in project churn and complexity

Usage
-----

		bin/turbulence -repo=/path/to/git/project -out=/tmp/output

It takes `/path/to/git/project` repository, calculates churn and complexity, then create an out.json file under `/tmp/output`.

		bin/turbulence -repo=/path/to/git/project -out=/tmp/output -path=src

When `-path` parameter presents only files (classes) under `src/` will be calculated.

If everything went well a `viewer.html` will be generated under output (`/tmp/output`).

Dependencies
------------

It uses [PDepend](http://pdepend.org/) to calculate complexity.

		pear channel-discover pear.pdepend.org
		pear install pdepend/PHP_Depend-beta

