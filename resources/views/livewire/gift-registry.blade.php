<div>
    <main id="main" class="site-main">
        <div class="site-content owner-content">
            <div class="container">
                <div class="member-place-wrap">
                    <div class="member-wrap-top">
                        <h2>{{ $event->event_name }}'s Gift Registry</h2>
                        <div>
                            <a href="{{ route('events.show', $event) }}">Back to My Event</a>
                            <button class="btn m-2" data-bs-toggle="modal" data-bs-target="#add-gift-{{ $event->id }}">Register Gift</button>
                            @include('partials.add-gift')
                        </div>
                    </div><!-- .member-place-wrap -->
                    <table class="member-place-list owner-booking table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 100px">Title</th>
                                <th>Description</th>
                                <th>Value</th>
                                <th>Received Date</th>
                                <th>Received By</th>
                                <th>Received From</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gifts as $gift)
                                <tr>
                                    @include('partials.edit-gift')
                                    <td data-title="ID">
                                        {{ $i++ }}
                                    </td>
                                    <td style="width: 100px">
                                        {{$gift->title }}
                                    </td>
                                    <td style="width: 200px">
                                        <span>{{ $gift->description }}</span>
                                    </td>
                                    <td style="width: 100px">
                                        <p>{{ $gift->value ? 'Ksh.'.$gift->value : ''}}</p>
                                    </td>
                                    <td>
                                        {{ $gift->received_date }}
                                    </td>
                                    <td>
                                        <span>{{ $gift->received_by }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $gift->received_from }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $gift->phone }}</span>
                                    </td>
                                    <td style="width: 150px">
                                        <span>
                                            <i class="las la-edit" data-bs-toggle="modal" data-bs-target="#edit-gift-{{ $gift->id }}" style="cursor: pointer"></i>
                                            <i class="las la-trash" wire:click="deleteGift({{ $gift->id }})" style="cursor: pointer"></i>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                        {{ $gifts->links() }}
                </div><!-- .member-wrap-top -->
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
</div>
