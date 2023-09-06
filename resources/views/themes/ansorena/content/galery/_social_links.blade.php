<div class="d-flex align-items-center justify-content-center gap-2 share-links">
    <a class="share-icon" target="_blank" title="facebook" href="http://www.facebook.com/sharer.php?u={{URL::full()}}">
        <svg width="8" height="13" viewBox="0 0 8 13" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M7.50313 0.00266311L5.84335 0C3.97864 0 2.77358 1.23635 2.77358 3.14993V4.60226H1.10474C0.960528 4.60226 0.84375 4.71917 0.84375 4.86338V6.96764C0.84375 7.11185 0.960661 7.22863 1.10474 7.22863H2.77358V12.5383C2.77358 12.6826 2.89035 12.7993 3.03456 12.7993H5.21192C5.35613 12.7993 5.47291 12.6824 5.47291 12.5383V7.22863H7.42417C7.56838 7.22863 7.68516 7.11185 7.68516 6.96764L7.68596 4.86338C7.68596 4.79414 7.65839 4.72783 7.60953 4.67883C7.56066 4.62983 7.49408 4.60226 7.42484 4.60226H5.47291V3.3711C5.47291 2.77936 5.61392 2.47896 6.38476 2.47896L7.50287 2.47856C7.64694 2.47856 7.76372 2.36165 7.76372 2.21758V0.263648C7.76372 0.119707 7.64708 0.00292943 7.50313 0.00266311Z"
                fill="currentColor" />
        </svg>
    </a>
    <a class="share-icon" target="_blank" title="twitter" href="http://twitter.com/share?text={{URL::full()}}&url={{URL::full()}}">
		@include('components.x-icon', ['size' => '12'])
    </a>
    <a class="share-icon" target="_blank" title="linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{URL::full()}}">
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M3.13068 1.60508C3.13068 2.23819 2.62181 2.75118 1.99375 2.75118C1.36569 2.75118 0.856825 2.23819 0.856825 1.60508C0.856825 0.972436 1.36569 0.458984 1.99375 0.458984C2.62181 0.458984 3.13068 0.972436 3.13068 1.60508ZM3.13985 3.66806H0.847656V11.0031H3.13985V3.66806ZM6.79911 3.66806H4.52159V11.0031H6.79957V7.15265C6.79957 5.01174 9.56349 4.83662 9.56349 7.15265V11.0031H11.8502V6.35863C11.8502 2.74614 7.76 2.87771 6.79911 4.65599V3.66806Z"
                fill="currentColor" />
        </svg>
    </a>
</div>
