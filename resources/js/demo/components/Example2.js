import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import TestHook from '../../components/TestHook'


export default class Example2 extends Component {
    render() {
        return (
            <div className="container">
                <div className="row justify-content-center mt-3 mb-3">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Example Component2</div>
                            <div className="card-body">I'm an example component!</div>

							<TestHook />


                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('example2')) {
    ReactDOM.render(<Example2 />, document.getElementById('example2'));
}
