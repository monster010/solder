@extends('layouts.app')

@section('content')

    <section class="section">
        <div class="columns">
            <div class="column is-one-quarter">
                <h1 class="title is-4">Settings</h1>
                <aside class="menu">
                    <ul class="menu-list">
                        <li>
                            <a href="/profile/tokens">
                                <span class="icon">
                                    <i class="fa fa-fw fa-user-circle-o"></i>
                                </span>
                                Personal Access Tokens
                            </a>
                        </li>
                        <li>
                            <a href="/profile/clients" class="is-active">
                                <span class="icon">
                                    <i class="fa fa-fw fa-exchange"></i>
                                </span>
                                OAuth Clients
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <div class="column">

                <div class="level">
                    &nbsp;
                </div>

                <passport-clients></passport-clients>
                <passport-authorized-clients></passport-authorized-clients>

            </div>
        </div>
    </section>
@endsection