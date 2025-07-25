# About this repository

>*Most of the changes we've
made for ourselves over the years
except for a few we no longer need
or just don't fit in style wise, but
including some that are hard to find
because either people forgot about
them or simply because they haven't
been released yet, a few we really love,
one we think is just ok, some we did
for free, some we did for money, some
for ourselves without permission and
some for friends as swaps but never on
time and always at our office in ~~Old Street~~
Herbrand Street.*

Thought Machine's fork of [Phorge](https://github.com/phorgeit/phorge).
Phorge itself is a community-maintained
fork of [Phabricator](http://phabricator.org),
developed and maintained by [the Phorge team](https://phorge.it).

Our changes are maintained on the `tm-master` branch.

We probably won't accept any pull requests for new features as this repository
and the associated changes are purely quality-of-life changes for our own
installation.

We likewise provide no guarantee you'll find the changes useful for your own
Phorge/Phabricator install.

However, we have documented the changes here so you can make up your own mind.

# Overview of Changes

Changes originally created in
[thought-machine/phabricator](https://github.com/thought-machine/phabricator)
are marked with *:

 * Aphlict
   * Added a mode to allow it to run in the foreground without debug messages. (*)

 * Applications
   * Added a handler for `git upload-archive` to Diffusion. (*)
   * Removed foist upon in DifferentialRevisionEditEngine. (*)

 * Auth
   * Modified the Google auth provider to support Cloud IAP. (*)

 * Celerity
   * Added a TMCelerity class for custom celerity resources eg. javascript. (*)

 * rsrc - js
   * Added safe landing js which provides a visual cue if an
   accepted revision hasn't passed CI. (*)

 * Daemon
   * Added prometheus gauge to track the number of phabricator daemons. (*)

 * Differential
   * Modified the revision controller to include safe landing js. (*)
   * Created a custom revision query bucket for review actions. (*)
   * Exposed a diff's lint status as an attachment in the
   `differential.diff.search` Conduit method. (*)
   * Enable bots to add Jira tickets to diffs.

 * Diffusion
   * Added Git upload archive workflow. (*)
   * Added prometheus metrics to track cluster sync successes and failures. (*)
   * Added herald rules and harbourmaster builds for refs. (*)

 * Drydock
   * Modified the working copy blueprint to rebase before trying to merge. (*)

 * Prometheus
   * Added prometheus metric infrastructure and application. (*)
   * Added phabricator up metric to indicate whether phabricator is up or not. (*)

 * People
   * *Change* to UserQuery conduit method
     * We always return the user's email as it is not private information in
       our organisation. (*)
 * Project
   * *Change* to ProjectBoardView controller
     * We allow this page to be `frameable` so we can
     embed it in other dashboards. (*)
   * *Change* to ProjectBoardTaskCard
     * We display the current status on the task.
   * Add project logical viewer for project or user function so
   we can do viewerprojects() in queries. (*)

 * Repository
   * Pointing clone URIs to the Phabricator URI even if a repo is hosted elsewhere. (*)
   * added buildable interface for push log for harbormaster builds for refs. (*)

*Note*: We previously maintained our changes in a separate repository,
[thought-machine/phabricator](https://github.com/thought-machine/phabricator);
all changes were copied over
[upon creation](https://github.com/thought-machine/phorge/commit/2409b3c90d4b896c83dd09eb7d570caba7126cd6)
of this repository. To see the changes in chronological order, please see
the [commit history](https://github.com/thought-machine/phabricator/commits/master/) of
thought-machine/phabricator. Changes are marked in the source files with "TM CHANGES".

# Installation Instructions

To install this repository, use:

```
git clone --single-branch --branch tm-master https://github.com/thought-machine/phorge tmphorge
```

# Dependencies

We use [composer](https://getcomposer.org/) to manage external
dependencies. [This](https://getcomposer.org/doc/01-basic-usage.md) page
gives more information on how to use it.

# Generating the library map

The modules in this repository need to be referenced in some auto-generated
files. To generate them, ensure you have followed the installation instructions
above, then run `arc liberate` from the root of this repository:

```
cd tmphorge
arc liberate
# Should output "Done."
```

**NOTE:** `arc liberate` automatically attempts to build `libxhpast` in
`/opt/libphutil/support/xhpast/` if it detects that it doesn't already exist.
This will fail unless `arc liberate` is run as root. It should only be necessary
to do this once, usually when you run `arc liberate` for the first time.

More information can be found
[here](https://secure.phabricator.com/book/phabcontrib/article/adding_new_classes/#initializing-a-library).
