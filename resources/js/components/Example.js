import React from 'react';
import ReactDOM from 'react-dom';

function Example() {
    return (
        <div className="container mx-auto">
            <div className="card">
                <div className="col-md-8 text-gray-900 max-w-fit mx-auto">
                    <div className="card">
                        <div className="card-header text-3xl underline text-bold">Example Component</div>

                        <div className="card-body">I'm an example component!</div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Example;

if (document.getElementById('root')) {
    ReactDOM.render(<Example />, document.getElementById('root'));
}
