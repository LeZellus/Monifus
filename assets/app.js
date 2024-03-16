import feather from "feather-icons";
feather.replace();

import 'lazysizes';

import { registerReactControllerComponents } from '@symfony/ux-react';
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));

import './styles/app.scss';
import './bootstrap';