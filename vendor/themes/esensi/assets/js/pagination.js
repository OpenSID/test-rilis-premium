class Pagination {
    constructor(paginationContainer) {
        this.paginationContainer = paginationContainer
    }
    generatePagination(dataPagination, callbackFunction) {
        const pagination = this.paginationContainer;
        pagination.innerHTML = '';

        const totalPages = dataPagination.meta.pagination.total_pages;
        const currentPage = dataPagination.meta.pagination.current_page;
        let pageNumber;
        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.innerText = `${i}`;
            pageLink.className = 'cursor-pointer relative inline-flex items-center px-4 py-2 text-sm ' + ((i === currentPage) ? ' active bg-indigo-600' : '');
            pageLink.onclick = i === currentPage ? function () {} : function () {
                pageNumber = i
                callbackFunction(pageNumber)
            };
            if (i === 1) {
                const previousLink = pageLink.cloneNode()
                previousLink.className = 'cursor-pointer items-center px-4 py-2' + ((currentPage <= 1) ? 'disabled' : '');
                previousLink.innerText = `<`;
                previousLink.onclick = currentPage > i ? function () {
                    pageNumber = currentPage - 1
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(previousLink);
            }
            pagination.appendChild(pageLink);
            if (i === totalPages) {
                const nextLink = pageLink.cloneNode()
                nextLink.className = 'cursor-pointer items-center px-4 py-2' + ((currentPage >= totalPages) ? 'disabled' : '');
                nextLink.innerText = `>`;
                nextLink.onclick = currentPage < totalPages ? function () {
                    pageNumber = currentPage + 1
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(nextLink);
            }
        }
    }
}
