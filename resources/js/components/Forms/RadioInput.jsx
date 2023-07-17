export const RadioInput = ({ id, text, checked, onChange }) => {
	return (
		<div className="form-check">
			<input type="radio" id={id} className="form-check-input" checked={checked} onChange={onChange} />
			<label htmlFor={id} className="form-check-label">
				{text}
			</label>
		</div>
	)
}
