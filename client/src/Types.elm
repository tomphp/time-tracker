module Types
    exposing
        ( Developer
        , Loadable(..)
        , Model
        , Msg(..)
        , Project
        , TimeEntry
        )

import Http
import Dict exposing (Dict)
import Material


type alias Project =
    { id : String
    , name : String
    , url : String
    , totalTime : Maybe String
    , entries : List TimeEntry
    }


type alias TimeEntry =
    { date : String
    , period : String
    , description : String
    , developer : String
    }


type alias Developer =
    { name : String
    , email : String
    , url : String
    }


type alias Model =
    { apiEndpoint : String
    , projectsEndpoint : Maybe String
    , developersEndpoint : Maybe String
    , projects : Maybe (Dict String Project)
    , project : Loadable Project
    , developers : Maybe (List Developer)
    , mdl : Material.Model
    }


type Loadable a
    = NotLoaded
    | Loading
    | Loaded a
    | Failed


type Msg
    = IndexFetched (Result Http.Error ( String, String ))
    | Refresh
    | Mdl (Material.Msg Msg)
    | ProjectsFetched (Result Http.Error (Dict String Project))
    | FetchProject String
    | ProjectFetched (Result Http.Error (Maybe Project))
    | DevelopersFetched (Result Http.Error (List Developer))
