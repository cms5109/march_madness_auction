# March Madness Calcutta Auction Web Interface
This was made to facilitate a Calcutta Auction for March Madness.

## Deployment Steps
The app is currently deployed on [heroku](https://dashboard.heroku.com/apps/march-maddness/). To be able to deploy, you need to have a Heroku account and be added as a collaborator to the application. 
1. Sign up for an account [here](https://signup.heroku.com/)
1. Ask [Conal](https://github.com/cms5109) to be added as a collaborator

After you have access:
1. Install the [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli)
1. Follow the [deployment steps](https://dashboard.heroku.com/apps/march-maddness/deploy/heroku-git)
    1. Either clone the heroku repo or set an upstream of `heroku` in your current repo - `git remote add heroku https://git.heroku.com/march-maddness.git`
    1. Add changes to the application; commit
    1. Deploy! `git push heroku master`


## References
  - [Calcutta Auction](https://en.wikipedia.org/wiki/Calcutta_auction)
