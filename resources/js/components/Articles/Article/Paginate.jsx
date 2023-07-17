import ReactPaginate from "react-paginate";

export function Paginate({ offset, pageCount, handlePageClick }) {

	return (
		<>
			{pageCount > 0 &&
				<ReactPaginate
					forcePage={offset}
					previousClassName="page-item"
					previousLinkClassName="page-link"
					nextClassName="page-item"
					nextLinkClassName="page-link"
					breakClassName="page-item disabled"
					breakLinkClassName="page-link"
					previousLabel={"‹"}
					nextLabel={"›"}
					breakLabel={"..."}
					pageCount={pageCount}
					marginPagesDisplayed={2}
					pageRangeDisplayed={3}
					onPageChange={handlePageClick}
					containerClassName={`pagination`}
					subContainerClassName={"pages pagination "}
					pageClassName={"page-item"}
					pageLinkClassName={"page-link"}
					activeClassName={"active"}
				/>
			}
		</>
	)
}
