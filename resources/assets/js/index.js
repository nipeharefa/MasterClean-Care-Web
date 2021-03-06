import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createStore } from 'redux'
import { ConnectedRouter, routerMiddleware } from 'react-router-redux'
import routes from './modules/routes'
import history from './modules/history'
import configureStore from './stores/configureStore'
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider'
import injectTapEventPlugin from 'react-tap-event-plugin'

injectTapEventPlugin()

const store = configureStore(routerMiddleware(history))

render(
    <MuiThemeProvider>
        <Provider store={ store }>
            <ConnectedRouter history={ history } >
                { routes(store) }
            </ConnectedRouter>
        </Provider>
    </MuiThemeProvider>,
    document.getElementById('root')
)


{/*<div>
        <routes />
        <Route path="/" component={App} onEnter={requireAuth} />
        <Route path="/login" component={LoginContainer} />
    </div>
</Router>*/}