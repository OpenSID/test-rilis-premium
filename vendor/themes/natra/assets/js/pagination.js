class Pagination {
    constructor(paginationContainer) {
        this.paginationContainer = paginationContainer
    }
    generatePagination(dataPagination, callbackFunction) {
        const pagination = this.paginationContainer;
        pagination.innerHTML = '';

        const totalPages = dataPagination.meta.pagination.total_pages;
        const currentPage = dataPagination.meta.pagination.current_page;
        const paginationInfo = pagination.parentNode.querySelector('.pagination-info')
        paginationInfo.innerText = `Halaman ${currentPage} dari ${totalPages}`

        let pageNumber;
        for (let i = 1; i <= totalPages; i++) {				
            const pageLink = document.createElement('li');
            pageLink.innerHTML = `<a href="#" class="page-link" title="Halaman ${i}">${i}</a>`;
            pageLink.className = (i === currentPage) ? 'active' : '';
            pageLink.onclick = i === currentPage ? function(){} : function() {					
                pageNumber = i
                callbackFunction(pageNumber)  
            };
            if(i === 1){
                const firstLink = pageLink.cloneNode()
                firstLink.className = 'page-item';
                firstLink.innerHTML = `<a href="#" class="page-link" title="Halaman Pertama"><i class="fa fa-fast-backward"></i>&nbsp;</a></li>`;
                firstLink.onclick = currentPage > i ? function () {
                    pageNumber = 1
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(firstLink);

                const previousLink = pageLink.cloneNode()
                previousLink.className = (currentPage <= 1) ? 'disabled' : '';
                previousLink.innerHTML = `<a href="#" class="page-link" title="Halaman Sebelumnya"><i class="fa fa-backward"></i>&nbsp;</a>`;
                previousLink.onclick = currentPage > i ? function() {					
                    pageNumber = currentPage - 1
                    callbackFunction(pageNumber)
                } : function(){};
                if(currentPage > 1){
                    pagination.appendChild(previousLink);
                }                
            }
            pagination.appendChild(pageLink);
            if(i === totalPages){
                const nextLink = pageLink.cloneNode()
                nextLink.className = (currentPage >= totalPages) ? 'disabled' : '';
                nextLink.innerHTML = `<a href="#" class="page-link"  title="Halaman Selanjutnya"><i class="fa fa-forward"></i>&nbsp;</a>`;
                nextLink.onclick = currentPage < totalPages ? function() {					
                    pageNumber = currentPage + 1
                    callbackFunction(pageNumber) 
                } : function(){};
                if(currentPage < totalPages){
                    pagination.appendChild(nextLink);
                }                

                const endLink = pageLink.cloneNode()
                endLink.className = 'page-item';
                endLink.innerHTML = `<a href="#" class="page-link" title="Halaman Terakhir"><i class="fa fa-fast-forward"></i>&nbsp;</a>`;
                endLink.onclick = currentPage < totalPages ? function () {
                    pageNumber = totalPages
                    callbackFunction(pageNumber)
                } : function () {};
                pagination.appendChild(endLink);
            }
        }
    }
}
