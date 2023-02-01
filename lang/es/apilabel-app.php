<?php

/*
    |--------------------------------------------------------------------------
    | Textos
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    |
    */

return
[



	#value :input
  'validation' =>
    array (
        'required' => "Error in the field :attribute, this field is mandatory" ,
        'email' => "Error in the field :attribute must have a valid email format, sended value: ':input'" ,
        'date' => "Error in the field :attribute must have a valid date format, sended value: ':input'" ,
        'numeric' => "Error in the field :attribute must be numeric, sended value: ':input'" ,
        'alpha_num' => "Error in the field :attribute must be alphanumeric, sended value: ':input'" ,
        'alpha' => "Error in the field :attribute must be alphabetical, sended value: ':input'" ,
        'min_characters' => "Error in the field :attribute  must have at least :min characters , sended value: ':input'" ,
        'max_characters' => "Error in the field :attribute must have at most :max characters , sended value: ':input'" ,
        'date_format' => "Error in the field :attribute Wrong date or time format, sended value: ':input'",
		'filled' => "Error in the field  :attribute cannot be empty",
		'required_without_all' => "Error in the field  :attribute, at least one of the field is mandatory",
		'not_be_modified' => "Error in the field  :attribute, this field cannot be modified",

    ),
    'success' =>
    array(
        'delete'  => 'At least one parameter is not correct',
        'unexpected_exception' => 'Unexpected Exception'
    ),
    'errors' =>
    array(
        'parameter_not_exist'  => 'At least one parameter is not correct',
        'unexpected_exception' => 'Unexpected Exception',
        'data_not_found' => "Data not found",
        'validation' => "Error in validation",
        'no_items' => "Array items are empty",
        'no_params' => "Array params are empty",
        'different_auctions' => 'all lots must belong to the same auction',
        'delete' => 'No items have been deleted, there are no items with the indicated identifier',
        'no_match' =>'There are no items with the indicated identifier',
		'img' => 'Error creating  image',
		'updating' =>'Error updating registry',
		'no_sessions' => "Array sessions are empty",
		"no_change_type_auction" => "changing the auction type is not allowed",
		"no_change_visiblebids" => "changing the visibility of bids is not allowed",
		"id_not_zero" => "id cannot be zero",
		"no_exist_serial" => "Serial code does not exist",
		"no_exist_client" => "The client code does not exist",
		"exist_bids" => "The bidder cannot be eliminated because they have bids or orders",
		'no_array' => "Error in the field :field, must be an array",
		'array_no_numeric' => "Error in the field :field, the elements of array it must be numerical ",
		'unique_constraint_violated' => "Unique constraint has been violated ",
    ),




    ];
