<?php namespace Opensim;

class AssetType {

        /// Unknown asset type 
        const Unknown = -1;
        /// Texture asset, stores in JPEG2000 J2C stream format
        const Texture = 0;
        /// Sound asset
        const Sound = 1;
        /// Calling card for another avatar
        const CallingCard = 2;
        /// Link to a location in world
        const Landmark = 3;
        /// Legacy script asset, you should never see one of these
        //[Obsolete]
        //Script = 4,
        /// Collection of textures and parameters that can be 
        /// worn by an avatar
        const Clothing = 5;
        /// Primitive that can contain textures, sounds, 
        /// scripts and more
        const Object = 6;
        /// Notecard asset
        const Notecard = 7;
        /// Holds a collection of inventory items
        const Folder = 8;
        /// Root inventory folder
        const RootFolder = 9;
        /// Linden scripting language script
        const LSLText = 10;
        /// LSO bytecode for a script
        const LSLBytecode = 11;
        /// Uncompressed TGA texture
        const TextureTGA = 12;
        /// Collection of textures and shape parameters that can
        /// be worn
        const Bodypart = 13;
        /// Trash folder
        const TrashFolder = 14;
        /// Snapshot folder
        const SnapshotFolder = 15;
        /// Lost and found folder
        const LostAndFoundFolder = 16;
        /// Uncompressed sound
        const SoundWAV = 17;
        /// Uncompressed TGA non-square image, not to be used as a
        /// texture
        const ImageTGA = 18;
        /// Compressed JPEG non-square image, not to be used as a
        /// texture
        const ImageJPEG = 19;
        /// Animation
        const Animation = 20;
        /// Sequence of animations, sounds, chat, and pauses
        const Gesture = 21;
        /// Simstate file
        const Simstate = 22;
    }