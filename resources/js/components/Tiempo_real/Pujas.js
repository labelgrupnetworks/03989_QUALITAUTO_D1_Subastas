import React, { useState } from 'react';

const tipoPuja = {
	I : 'fa-globe',
	S : 'fa-hand-paper-o',
	T : 'fa-phone',
	W : 'fa-wikipedia-w'
}

const Pujas = ({socket, pujasIniciales}) => {
  const [pujas, setPujas] = useState(pujasIniciales);

  socket.on('action_response', ({pujasAll}) => {

	//console.log(data);

	if(pujasAll != undefined){
		console.log(pujasAll);
		setPujas(pujasAll);
	  }
	});

  return (
	<div className="aside">
		{pujas.map((puja) => {

			return (
				<div key={puja.rn}>
					<div className="col-7 tipoPuja">
						<p data-type={puja.pujrep_asigl1}><i className={`fa ${tipoPuja[puja.pujrep_asigl1]}`} aria-hidden="true"></i> Internacional</p>
					</div>
					<div className="col-5 text-center importePuja">
						<p className="puj_imp">{`${puja.formatted_imp_asigl1}€`}</p>
					</div>
				</div>
			)}
		)}
    </div>
  );
}

export default Pujas;
