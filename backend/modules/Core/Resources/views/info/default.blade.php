<div class="entity-wrapper">
    <div class="entity-info-wrapper">
        <h2>{{ $entity['entity_name'] }}-{{ $entity['id'] }}</h2>

        <div>
            <span class="font-weight-bold">id:</span> {{ $entity['id'] }}
        </div>

        <div>
            <span class="font-weight-bold">Name:</span> {{ $entity['display_name'] ?: $entity['name'] ?: 'Not found' }}
        </div>

        <div>
            <span class="font-weight-bold">Link:</span>
            <a href='{{ $entity['url_for_qr_code'] }}'>{{ $entity['url_for_qr_code'] }}</a>
        </div>

        <div class="qr-code-link">
            <span class="font-weight-bold">QR code:</span>
            <a href='{{ asset($entity['qr_code_path']) }}'>{{ asset($entity['qr_code_path']) }}</a>
        </div>
    </div>

    <div class="qr-code-img">
        <img src="{{ asset($entity['qr_code_path']) }}" alt="{{ asset($entity['qr_code_path']) }}">
    </div>
</div>
